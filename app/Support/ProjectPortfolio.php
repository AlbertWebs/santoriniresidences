<?php

namespace App\Support;

class ProjectPortfolio
{
    public static function periods(): array
    {
        return [
            [
                'id' => '2012-2015',
                'period' => '2012 — 2015',
                'note' => 'Residential foundations and early commercial work',
                'projects' => [
                    self::project('Oct 2012 – Dec 2014', 'Baili Mingzhu Garden (Phases I & II)', '百丽明珠花园一期、二期工程', 'Residential', 'Lukou Subdistrict, Jiangning District, Nanjing', '52,005 m²', 'baili-mingzhu-garden', 'Aerial view of Baili Mingzhu Garden, with residential blocks arranged around landscaped courtyards.'),
                    self::project('Jun 2014 – Nov 2015', 'Ziyuan Residential Community', '梓园住宅小区', 'Residential', 'Beiling Road, Gaochun District, Nanjing', '9,614 m²', 'ziyuan-residential-community', 'Residential towers and landscaped grounds at Ziyuan Residential Community.'),
                    self::project('Jul 2014 – 2018', 'Zhongju Tower', '中聚大厦', 'Commercial / Office', 'Yulongshan Road, Jianye District, Nanjing', '38,999 m²', 'zhongju-tower', 'Architectural rendering of Zhongju Tower, a contemporary office building in Nanjing.'),
                    self::project('Oct 2014 – Oct 2015', 'Eco-Life Science Park (Phases I & II)', '生态生命科学园一期、二期', 'Institutional / Science Park', 'Pukou High-Tech Industrial Development Zone, Nanjing', '12,000 m²', 'eco-life-science-park', 'Interior corridor at the Eco-Life Science Park.'),
                ],
            ],
            [
                'id' => '2016-2019',
                'period' => '2016 — 2019',
                'note' => 'New communities, public realm and design-and-build delivery',
                'projects' => [
                    self::project('Apr 2016 – Jun 2018', 'Qingxiang Yayuan', '清香雅苑', 'Residential', 'Xiongzhou Subdistrict, Liuhe District, Nanjing', '42,898 m²', 'qingxiang-yayuan', 'Landscaped public forecourt at Qingxiang Yayuan.'),
                    self::project('Mar 2017 – Jun 2019', 'Yangguang Hetian Residential Community', '阳光禾田住宅小区', 'Residential · Self-developed', 'Renmin Road, Hongze District, Huai’an', '30,575 m²', 'yangguang-hetian-community', 'Aerial architectural view of Yangguang Hetian Residential Community.'),
                    self::project('2018', 'Lishui Wuxiang International Pile Foundation Project', '', 'Foundation / Specialist works', 'Lishui, Nanjing', 'Large-scale pile foundations'),
                    self::project('Completed 2019', 'Hetian Fengguang', '禾田风光', 'Residential · Self-developed', 'East of Yanlin River, Hongze District, Huai’an', '4,234 m²', 'hetian-fengguang', 'Evening view of the Hetian Fengguang residential development beside landscaped water.'),
                    self::project('Sep 2018 – Jan 2020', 'Suining Guanghua Phase V Residential Community', '睢宁光华五期住宅小区', 'Residential · Design & build', 'Huanyu West Road, Suining County, Xuzhou', '77,355 m²', 'suining-guanghua-phase-v', 'Residential buildings and green grounds at Suining Guanghua Phase V.'),
                ],
            ],
            [
                'id' => '2020-2022',
                'period' => '2020 — 2022',
                'note' => 'Civic, educational, healthcare and mixed-use environments',
                'projects' => [
                    self::project('Completed 2020', 'Hongze Public Health Centre', '洪泽县公共卫生中心', 'Public / Healthcare', 'Renmin Road, Hongze District, Huai’an', '32,649 m²'),
                    self::project('Dec 2019 – Jan 2020', 'Xiaowangsheng Rural Landscaping Project, Phase I', '肖王盛特色田园乡村一期项目景观绿化', 'Landscape / Public realm', 'Chengqiao Subdistrict, Liuhe District, Nanjing', '5,000 m² · landscaping', 'xiaowangsheng-rural-landscaping', 'Contemporary public courtyard and landscaped pedestrian space at the Xiaowangsheng rural landscaping project.'),
                    self::project('Jun 2020 – Aug 2020', 'Nanjing No. 1 High School Junior Campus Renovation', '南京市第一中学初中部改造', 'Institutional / Education', 'Xianlin, Nanjing', '3,842 m² · fit-out', 'nanjing-no1-high-school-junior-campus', 'Renovated exterior and courtyard at the Nanjing No. 1 High School junior campus.'),
                    self::project('Oct 2020 – Dec 2020', 'Zhixin College Experimental Teaching Centre Renovation', '南京中医药大学智信学院实验教学中心改造工程', 'Institutional / Education', 'Qilicun, Qinhuai District, Nanjing', '5,320 m²', 'zhixin-college-teaching-centre', 'Computer teaching room at the Zhixin College Experimental Teaching Centre.'),
                    self::project('Nov 2020 – Oct 2022', 'Tianhong Street Centre Project', '天虹街坊中心项目', 'Mixed-use', 'Yanhe Road / Yong’an Road, Suining County, Xuzhou', '46,395 m²', 'tianhong-street-centre', 'Street-facing exterior of the Tianhong Street Centre Project.'),
                    self::project('Jun 2021 – Apr 2022', 'Public Security Bureau Complex Renovation', '南京市公安局综合楼改造', 'Government', 'Ningshuang Road, Yuhuatai District, Nanjing', '5,287 m²', 'nanjing-public-security-bureau', 'Refined interior corridor at the Public Security Bureau complex.'),
                    self::project('Jul 2021 – Aug 2021', 'Building B4 Teaching Block Full Renovation', '南京中医药大学B4教学楼整体出新', 'Institutional / Education', 'Xianlin, Nanjing', '6,122 m² · fit-out', 'b4-teaching-block', 'Exterior view of the fully renovated Building B4 teaching block.'),
                    self::project('Aug 2021 – Oct 2021', 'Library & Museum Underground Car Park Renovation', '图书馆及博物馆地下车库改造工程', 'Institutional', 'Xianlin, Nanjing', '6,089 m² · fit-out', 'library-museum-car-park', 'Renovated underground car park serving the library and museum.'),
                ],
            ],
            [
                'id' => '2023-2025',
                'period' => '2023 — 2025',
                'note' => 'Campus-scale works and specialist industrial delivery',
                'projects' => [
                    self::project('Oct 2022 – Nov 2023', 'Juhuiyuan External Works Project', '聚慧园室外配套工程', 'Mixed-use / R&D & office campus', 'Jiangbei New Area, Nanjing', '273,110 m² · masterplan', 'juhuiyuan-external-works', 'Interior lounge at the Juhuiyuan research and office campus.'),
                    self::project('Oct 2023 – Jul 2025', 'Commercial Kitchen Equipment Manufacturing Facility, Phase II Workshop 2', '年产1500套商用厨房设备制造项目二期厂房二', 'Industrial', 'Nanjing', 'Industrial facility'),
                ],
            ],
            [
                'id' => 'specialist-projects',
                'period' => 'Infrastructure & specialist works',
                'note' => 'Additional project examples from the company profile',
                'projects' => [
                    self::project('Profile project', 'Nantong Metro Line 2 Diaphragm Wall Construction Works', '', 'Underground infrastructure', 'Nantong, Jiangsu', 'Diaphragm wall construction'),
                    self::project('Profile project', 'Liuhe Annual-Production Steel Structure Project', '', 'Steel structure / Industrial', 'Liuhe', 'Steel structure works'),
                    self::project('Profile project', 'Wanxiang Duhui', '', 'Decoration & fit-out', 'Yongle Road, Kazimen, Qinhuai District, Nanjing', 'Approx. 28,450 m²', 'wanxiang-duhui', 'Interior lobby at Wanxiang Duhui, a mixed-use project in Nanjing.'),
                    self::project('Profile project', 'Nanjing University of Aeronautics and Astronautics Renovation Project', '', 'Institutional / Renovation', 'Nanjing', 'Renovation works', 'nuaa-renovation', 'Landscaped courtyard at the Nanjing University of Aeronautics and Astronautics renovation project.'),
                    self::project('Profile project', 'Tanggou Brand Operations (Nanjing) Co., Ltd. — Shangshuli No. 48 Operations Center Renovation', '', 'Decoration & fit-out', 'No. 48 Shangshuli, Qinhuai District, Nanjing', 'Approx. 2,648.16 m² · renovation', 'tanggou-shangshuli-48', 'Meeting room within the Shangshuli No. 48 Operations Center renovation.', 'The works included façade refurbishment, equipment procurement and installation, interior upgrading, repairs, landscape, water, strengthening, wall, kitchen and installation works.'),
                    self::project('Profile project', 'Xianxin Road Project', '', 'Highway subgrade works', '', 'Provincial key road and bridge project', 'xianxin-road', 'Aerial view of the Xianxin Road project and its highway corridor.', 'Works included main-line bridges, Chuhe auxiliary-road bridges, at-grade roads, flood-control compensation works for Chuhe Bridge, subgrade, pavement and ancillary works.'),
                    self::project('Profile project', 'Leying Steel Structure Factory Building Project', '', 'Steel structure / Industrial', 'No. 9 Wuge Road, Airport Economic Development Zone, Jiangning District, Nanjing', 'Approx. 24,660.70 m²', 'leying-steel-structure-factory', 'Multi-storey steel-frame factory building under construction at the Leying project.', 'The multi-storey factory uses a light-gauge steel exterior-wall system with corrugated metal panels and pile foundations.'),
                    self::project('Profile project', 'Steel Structure Works for the Aviation Industry Airborne/Airdrop Construction Equipment Project', '', 'Steel structure / Aviation', 'East of Xumutang Road, Lishui Development Zone, Nanjing, Jiangsu', 'Steel structures, canopies and roof purlins', 'aviation-steel-structure-works', 'Architectural rendering of a manufacturing building included in the aviation industry steel structure project.', 'Works included structures and canopies for processing, assembly, delivery, textile and sewing facilities, plus roof purlins for a chemical warehouse.'),
                ],
            ],
        ];
    }

    private static function project(string $date, string $name, string $local, string $sector, string $location, string $area, ?string $image = null, ?string $alt = null, ?string $summary = null): array
    {
        return compact('date', 'name', 'local', 'sector', 'location', 'area', 'image', 'alt', 'summary');
    }
}
