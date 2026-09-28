<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\Media;
use App\Support\MediaProcessor;
use App\Support\ResponsiveImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

class MediaController extends Controller
{
    private const EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'mp4'];

    public function index(): View
    {
        $items = Media::latest('id')->get();

        return view('admin.media.index', [
            'items' => $items,
            'stats' => [
                'images' => $items->where('kind', 'image')->count(),
                'films' => $items->where('kind', 'video')->count(),
                'uploads' => $items->where('is_upload', true)->count(),
                'size' => $items->where('is_upload', true)->sum('size'),
            ],
        ]);
    }

    public function library(): JsonResponse
    {
        return response()->json(['data' => Media::latest('id')->get()]);
    }

    /**
     * Receive one 5 MB part of an upload; the final part assembles and processes the file.
     */
    public function chunk(Request $request, MediaProcessor $processor): JsonResponse
    {
        $data = $request->validate([
            'upload_id' => ['required', 'string', 'regex:/^[A-Za-z0-9\-]{8,64}$/'],
            'index' => ['required', 'integer', 'min:0'],
            'total' => ['required', 'integer', 'min:1', 'max:150'],
            'name' => ['required', 'string', 'max:200'],
            'size' => ['required', 'integer', 'min:1', 'max:'.MediaProcessor::MAX_VIDEO_BYTES],
            'chunk' => ['required', 'file', 'max:6144'],
        ]);

        $extension = strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));

        if (! in_array($extension, self::EXTENSIONS, true)) {
            throw ValidationException::withMessages(['chunk' => 'Only JPG, PNG, WebP images and MP4 films can be uploaded.']);
        }

        if ($extension !== 'mp4' && $data['size'] > MediaProcessor::MAX_IMAGE_BYTES) {
            throw ValidationException::withMessages(['chunk' => 'Images must be 25 MB or smaller.']);
        }

        if ($data['index'] >= $data['total']) {
            throw ValidationException::withMessages(['chunk' => 'This part does not belong to the upload.']);
        }

        $directory = Storage::disk('local')->path('chunks/'.$data['upload_id']);
        File::ensureDirectoryExists($directory);
        $request->file('chunk')->move($directory, 'part-'.str_pad((string) $data['index'], 4, '0', STR_PAD_LEFT));

        $parts = glob($directory.DIRECTORY_SEPARATOR.'part-*') ?: [];

        if (count($parts) < $data['total']) {
            return response()->json(['done' => false, 'received' => count($parts)]);
        }

        sort($parts);
        $assembled = $directory.DIRECTORY_SEPARATOR.'assembled.'.$extension;

        try {
            $out = fopen($assembled, 'wb');
            foreach ($parts as $part) {
                $in = fopen($part, 'rb');
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
            fclose($out);

            if (filesize($assembled) !== (int) $data['size']) {
                throw new RuntimeException('The upload arrived incomplete. Please try again.');
            }

            $media = $processor->store($assembled, $data['name']);
        } catch (RuntimeException $exception) {
            throw ValidationException::withMessages(['chunk' => $exception->getMessage()]);
        } finally {
            File::deleteDirectory($directory);
        }

        return response()->json(['done' => true, 'media' => $media->fresh()]);
    }

    public function update(Request $request, Media $media): JsonResponse
    {
        $data = $request->validate(['alt' => ['nullable', 'string', 'max:300']]);
        $media->update(['alt' => $data['alt'] ?? '']);

        return response()->json(['media' => $media->fresh()]);
    }

    public function destroy(Media $media): JsonResponse
    {
        if ($media->is_upload) {
            Storage::disk('public')->delete(substr($media->path, strlen('storage/')));
            ResponsiveImage::delete($media->path);
        }

        $this->detachFromContent($media->path);
        $media->delete();

        return response()->json(['deleted' => true]);
    }

    private function detachFromContent(string $path): void
    {
        $replace = function ($value) use (&$replace, $path) {
            if (is_array($value)) {
                return array_map($replace, $value);
            }

            return $value === $path ? '' : $value;
        };

        ContentBlock::query()->where('data', 'like', '%'.str_replace('/', '%', $path).'%')->get()
            ->each(fn (ContentBlock $block) => $block->update(['data' => $replace($block->data)]));

        cms()->flush();
    }
}
