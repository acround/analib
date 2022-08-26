<?php

/**
 *
 * @author acround
 */

namespace analib\Core\Xml\Fb2;

class Fb2Genres
{

    private static array $FB20_FB21_recoding = [
        'architecture' => 'design',
        'art' => 'design',
        'art_instr' => 'design',
        'artists' => 'design',
        'biography' => 'nonf_biography',
        'biogr_arts' => 'nonf_biography',
        'biogr_ethnic' => 'nonf_biography',
        'biogr_family' => 'nonf_biography',
        'biogr_historical' => 'nonf_biography',
        'biogr_leaders' => 'nonf_biography',
        'biogr_professionals' => 'nonf_biography',
        'biogr_sports' => 'nonf_biography',
        'biogr_travel' => 'nonf_biography',
        'biz_accounting' => 'sci_business',
        'biz_beogr' => 'nonf_biography',
        'biz_careers' => 'sci_business',
        'biz_economics' => 'sci_business',
        'biz_finance' => 'sci_business',
        'biz_international' => 'sci_business',
        'biz_investing' => 'sci_business',
        'biz_life' => 'sci_business',
        'biz_management' => 'sci_business',
        'biz_personal_fin' => 'sci_business',
        'biz_professions' => 'sci_business',
        'biz_ref' => 'sci_business',
        'biz_sales' => 'sci_business',
        'biz_small_biz' => 'sci_business',
        'business' => 'sci_business',
        'child_3' => 'child_tale',
        'child_4' => 'child_prose',
        'child_9' => 'child_prose',
        'child_animals' => 'adv_animal',
        'child_art' => 'children',
        'child_characters' => 'child_prose',
        'child_computers' => 'computers',
        'child_edu' => 'child_education',
        'child_history' => 'child_prose',
        'child_nature' => 'child_education',
        'child_obsessions' => 'children',
        'child_people' => 'children',
        'child_ref' => 'children',
        'child_religion' => 'religion_rel',
        'child_series' => 'children',
        'child_sports' => 'children',
        'chris_bibles' => 'religion_rel',
        'chris_catholicism' => 'religion_rel',
        'chris_devotion' => 'religion_rel',
        'chris_edu' => 'religion_rel',
        'chris_evangelism' => 'religion_rel',
        'chris_fiction' => 'religion_rel',
        'chris_history' => 'religion_rel',
        'chris_holidays' => 'child_prose',
        'chris_jesus' => 'religion_rel',
        'chris_living' => 'religion_rel',
        'chris_mormonism' => 'religion_rel',
        'chris_ñlergy' => 'religion_rel',
        'chris_orthodoxy' => 'religion_rel',
        'chris_pravoslavie' => 'religion_rel',
        'chris_protestantism' => 'religion_rel',
        'chris_ref' => 'religion_rel',
        'chris_theology' => 'religion_rel',
        'Christianity' => 'religion_rel',
        'comp_biz' => 'computers',
        'comp_cert' => 'computers',
        'comp_db' => 'comp_db',
        'comp_games' => 'computers',
        'comp_graph' => 'computers',
        'comp_hardware' => 'comp_hard',
        'comp_microsoft' => 'computers',
        'comp_networking' => 'comp_osnet',
        'comp_office' => 'computers',
        'comp_os' => 'comp_osnet',
        'comp_programming' => 'comp_programming',
        'comp_sci' => 'computers',
        'comp_software' => 'comp_soft',
        'compusers' => 'computers',
        'comp_www' => 'comp_www',
        'cook_appliances' => 'home_cooking',
        'cook_art' => 'home_cooking',
        'cook_baking' => 'home_cooking',
        'cook_can' => 'home_cooking',
        'cook_diet' => 'home_cooking',
        'cook_drink' => 'home_cooking',
        'cook_gastronomy' => 'home_cooking',
        'cooking' => 'home_cooking',
        'cook_meals' => 'home_cooking',
        'cook_natural' => 'home_cooking',
        'cook_outdoor' => 'home_cooking',
        'cook_pro' => 'home_cooking',
        'cook_quick' => 'home_cooking',
        'cook_ref' => 'home_cooking',
        'cook_regional' => 'home_cooking',
        'cook_spec' => 'home_cooking',
        'cook_veget' => 'home_cooking',
        'entertainment' => 'home_entertain',
        'entert_comics' => 'home_entertain',
        'entert_games' => 'home_entertain',
        'entert_humor' => 'home_entertain',
        'entert_movies' => 'home_entertain',
        'entert_music' => 'home_entertain',
        'entert_radio' => 'home_entertain',
        'entert_tv' => 'home_entertain',
        'family_activities' => 'home',
        'family_adoption' => 'home',
        'family_aging_parents' => 'home',
        'family_edu' => 'home',
        'family_fertility' => 'home_health',
        'family_health' => 'home_health',
        'family' => 'home',
        'family_humor' => 'humor',
        'family_lit_guide' => 'home',
        'family_parenting' => 'home_health',
        'family_pregnancy' => 'home_health',
        'family_ref' => 'reference',
        'family_relations' => 'home',
        'family_special_needs' => 'home_health',
        'fantasy_alt_hist' => 'sf_history',
        'fashion' => 'design',
        'gaming' => 'sf',
        'gay_biogr' => 'nonf_biography',
        'gay_mystery' => 'sf_horror',
        'gay_nonfiction' => 'nonfiction',
        'gay_parenting' => 'home',
        'gay_travel' => 'adv_geo',
        'graph_design' => 'design',
        'health_aging' => 'sci_medicine',
        'health_alt_medicine' => 'sci_medicine',
        'health_beauty' => 'home_health',
        'health_cancer' => 'sci_medicine',
        'health_death' => 'home_health',
        'health_dideases' => 'home_health',
        'health_diets' => 'home_health',
        'health_first_aid' => 'home_health',
        'health_fitness' => 'home_health',
        'health' => 'home_health',
        'health_men' => 'home_health',
        'health_mental' => 'sci_psychology',
        'health_nutrition' => 'home_health',
        'health_personal' => 'home_health',
        'health_psy' => 'sci_psychology',
        'health_recovery' => 'home_health',
        'health_ref' => 'home_health',
        'health_rel' => 'home_health',
        'health_self_help' => 'home_health',
        'health_sex' => 'home_sex',
        'health_women' => 'home_health',
        'histor_military' => 'sci_history',
        'history_africa' => 'sci_history',
        'history_america' => 'sci_history',
        'history_ancient' => 'sci_history',
        'history_asia' => 'sci_history',
        'history_australia' => 'sci_history',
        'history_europe' => 'sci_history',
        'history_gay' => 'sci_history',
        'history_jewish' => 'sci_history',
        'history_middle_east' => 'sci_history',
        'history_military_science' => 'sci_history',
        'history_russia' => 'sci_history',
        'history_study' => 'sci_history',
        'history_usa' => 'sci_history',
        'history_world' => 'sci_history',
        'home_collect' => 'home_crafts',
        'home_cottage' => 'home',
        'home_crafts' => 'home_crafts',
        'home_design' => 'home_diy',
        'home_entertain' => 'home_entertain',
        'home_expert' => 'home_diy',
        'home_garden' => 'home_garden',
        'home_howto' => 'home_diy',
        'home_interior_design' => 'home_diy',
        'home_pets' => 'home_pets',
        'home_weddings' => 'home',
        'horror_antology' => 'sf_horror',
        'horror_british' => 'sf_horror',
        'horror_erotic' => 'sf_horror',
        'horror_fantasy' => 'sf_horror',
        'horror_ghosts' => 'sf_horror',
        'horror_graphic' => 'sf_horror',
        'horror_occult' => 'sf_horror',
        'horror_ref' => 'sf_horror',
        'horror' => 'sf_horror',
        'horror_usa' => 'sf_horror',
        'horror_vampires' => 'sf_horror',
        'literature_adv' => 'adventure',
        'literature_antology' => 'prose_classic',
        'literature_books' => 'prose_classic',
        'literature_british' => 'prose_classic',
        'literature_classics' => 'prose_classic',
        'literature_critic' => 'prose_history',
        'literature_drama' => 'prose_classic',
        'literature_erotica' => 'love_eroti',
        'literature_essay' => 'prose_classic',
        'literature_fairy' => 'child_tale',
        'literature_gay' => 'prose_counter',
        'literature_history' => 'prose_history',
        'literature_letters' => 'nonf_biography',
        'literature_men_advent' => 'adventure',
        'literature_poetry' => 'poetry',
        'literature_political' => 'prose_contemporary',
        'literature' => 'prose_classic',
        'literature_religion' => 'religion_rel',
        'literature_rus_classsic' => 'prose_rus_classic',
        'literature_saga' => 'prose_classic',
        'literature_sea' => 'adv_maritime',
        'literature_short' => 'prose_classic',
        'literature_sports' => 'home_sport',
        'literature_su_classics' => 'prose_su_classics',
        'literature_usa' => 'prose_classic',
        'literature_war' => 'prose_contemporary',
        'literature_western' => 'adv_western',
        'literature_women' => 'love_contemporary',
        'literature_world' => 'prose_classic',
        'mystery' => 'det_history',
        'nonfiction_antropology' => 'sci_history',
        'nonfiction_avto' => 'nonfiction',
        'nonfiction_crime' => 'nonfiction',
        'nonfiction_demography' => 'nonfiction',
        'nonfiction_edu' => 'science',
        'nonfiction_emigration' => 'nonfiction',
        'nonfiction_ethnology' => 'science',
        'nonfiction_events' => 'nonfiction',
        'nonfiction_folklor' => 'antique_myths',
        'nonfiction_gender' => 'science',
        'nonfiction_gerontology' => 'science',
        'nonfiction_gov' => 'science',
        'nonfiction_holidays' => 'science',
        'nonfiction_hum_geogr' => 'science',
        'nonfiction_law' => 'sci_juris',
        'nonfiction_methodology' => 'science',
        'nonfiction_philantropy' => 'nonfiction',
        'nonfiction_philosophy' => 'sci_philosophy',
        'nonfiction_politics' => 'nonfiction',
        'nonfiction_pop_culture' => 'nonfiction',
        'nonfiction_pornography' => 'home_sex',
        'nonfiction_racism' => 'nonfiction',
        'nonfiction_ref' => 'reference',
        'nonfiction_research' => 'science',
        'nonfiction_social_sci' => 'science',
        'nonfiction_social_work' => 'science',
        'nonfiction_sociology' => 'science',
        'nonfiction_spec_group' => 'science',
        'nonfiction_stat' => 'science',
        'nonfiction_traditions' => 'nonfiction',
        'nonfiction_transportation' => 'nonfiction',
        'nonfiction_true_accounts' => 'nonfiction',
        'nonfiction_urban' => 'nonfiction',
        'nonfiction_women' => 'nonfiction',
        'outdoors_birdwatching' => 'sci_biology',
        'outdoors_conservation' => 'nonfiction',
        'outdoors_ecology' => 'sci_biology',
        'outdoors_ecosystems' => 'sci_biology',
        'outdoors_env' => 'sci_biology',
        'outdoors_fauna' => 'sci_biology',
        'outdoors_field_guides' => 'ref_guide',
        'outdoors_flora' => 'sci_biology',
        'outdoors_hiking' => 'home_crafts',
        'outdoors_hunt_fish' => 'home_crafts',
        'outdoors_nature_writing' => 'sci_biology',
        'outdoors_outdoor_recreation' => 'home_sport',
        'outdoors_ref' => 'sci_biology',
        'outdoors_resources' => 'science',
        'outdoors_survive' => 'home_sport',
        'outdoors_travel' => 'adv_geo',
        'people' => 'nonf_biography',
        'performance' => 'dramaturgy',
        'photography' => 'design',
        'popadanec' => 'sf_history',
        'professional_edu' => 'science',
        'professional_enginering' => 'sci_tech',
        'professional_finance' => 'sci_business',
        'professional_law' => 'sci_juris',
        'professional_management' => 'sci_business',
        'professional_medical' => 'sci_medicine',
        'professional_sci' => 'sci_tech',
        'ref_almanacs' => 'ref_ref',
        'ref_books' => 'reference',
        'ref_careers' => 'ref_ref',
        'ref_catalogs' => 'ref_ref',
        'ref_cons_guides' => 'ref_ref',
        'ref_dict' => 'ref_ref',
        'ref_edu' => 'sci_business',
        'ref_encyclopedia' => 'ref_encyc',
        'references' => 'reference',
        'ref_etiquette' => 'reference',
        'ref_fun' => 'reference',
        'ref_genealogy' => 'sci_history',
        'ref_langs' => 'reference',
        'ref_quotations' => 'reference',
        'ref_study_guides' => 'ref_ref',
        'ref_words' => 'reference',
        'ref_writing' => 'prose_contemporary',
        'religion_bibles' => 'religion_rel',
        'religion_buddhism' => 'religion',
        'religion_earth' => 'religion',
        'religion_east' => 'religion_self',
        'religion_fiction' => 'religion_rel',
        'religion_hinduism' => 'religion',
        'religion_islam' => 'religion',
        'religion_judaism' => 'religion',
        'religion_new_age' => 'religion_rel',
        'religion_occult' => 'religion_esoterics',
        'religion_other' => 'religion',
        'religion' => 'religion_rel',
        'religion_religious_studies' => 'religion_rel',
        'religion_spirituality' => 'religion_esoterics',
        'romance_anthologies' => 'prose_classic',
        'romance_contemporary' => 'prose_contemporary',
        'romance_fantasy' => 'sf_fantasy',
        'romance_historical' => 'prose_history',
        'romance_multicultural' => 'prose_contemporary',
        'romance' => 'prose_classic',
        'romance_regency' => 'prose_classic',
        'romance_religion' => 'religion_rel',
        'romance_romantic_suspense' => 'love_contemporary',
        'romance_series' => 'prose_contemporary',
        'romance_sf' => 'sf',
        'romance_time_travel' => 'sf',
        'science_agri' => 'science',
        'science_archaeology' => 'sci_history',
        'science_astronomy' => 'science',
        'science_behavioral sciences' => 'sci_psychology',
        'science_biolog' => 'sci_biology',
        'science_chemistry' => 'sci_chem',
        'science_earth' => 'science',
        'science_eco' => 'science',
        'science_edu' => 'science',
        'science_evolution' => 'science',
        'science_history_philosophy' => 'sci_history',
        'science_math' => 'sci_math',
        'science_measurement' => 'science',
        'science_medicine' => 'sci_medicine',
        'science_physics' => 'sci_phys',
        'science_psy' => 'sci_psychology',
        'science_ref' => 'science',
        'science' => 'science',
        'science_technology' => 'sci_tech',
        'sf_cyber_punk' => 'sf_cyberpunk',
        'sf_writing' => 'sf',
        'sport' => 'home_sport',
        'teens_beogr' => 'nonf_biography',
        'teens_health' => 'home_sport',
        'teens_history' => 'adv_history',
        'teens_horror' => 'sf_horror',
        'teens_literature' => 'prose_classic',
        'teens_mysteries' => 'detective',
        'teens_ref' => 'reference',
        'teens_religion' => 'religion_rel',
        'teens_school_sports' => 'home',
        'teens_series' => 'child_adv',
        'teens_sf' => 'sf',
        'teens_social' => 'sci_psychology',
        'teens_tech' => 'science',
        'thriller_mystery' => 'detective',
        'thriller_police' => 'det_police',
        'thriller' => 'thriller',
        'travel' => 'adv_geo',
        'travel_africa' => 'adv_geo',
        'travel_asia' => 'adv_geo',
        'travel_australia' => 'adv_geo',
        'travel_canada' => 'adv_geo',
        'travel_caribbean' => 'adv_geo',
        'travel_europe' => 'adv_geo',
        'travel_ex_ussr' => 'adv_geo',
        'travel_guidebook_series' => 'adv_geo',
        'travel_lat_am' => 'adv_geo',
        'travel_middle_east' => 'adv_geo',
        'travel_polar' => 'adv_geo',
        'travel_rus' => 'adv_geo',
        'travel_spec' => 'adv_geo',
        'travel_usa' => 'adv_geo',
        'women_child' => 'home',
        'women_divorce' => 'home',
        'women_domestic' => 'home',
        'women_single' => 'home',
    ];
    private static array $typeGenreNames = [
        'adventure' => 'Приключения',
        'antique' => 'Прочая старинная литература (то, что не вошло в другие категории)',
        'aphorisms' => 'Афоризмы',
        'children' => 'Детские',
        'computers' => 'Компьютеры',
        'design' => 'Искусство и Дизайн',
        'detective' => 'Детектив',
        'dramaturgy' => 'Драматургия',
        'economics' => 'Экономика',
        'home' => 'Дом',
        'humor' => 'Юмор',
        'love' => 'Романтическая литература',
        'network_literature' => 'Сетевая литература',
        'nonfiction' => 'Документальная литература',
        'poetry' => 'Поэзия',
        'prose' => 'Проза',
        'reference' => 'Справочная литература',
        'science' => 'Научная литература',
        'sf' => 'Научная Фантастика',
        'thriller' => 'Триллер',
        'religion' => 'Религия',
    ];
    private static array $genreNames = [
        'adventure' => [
            'adv_western' => 'Вестерн',
            'adv_history' => 'Исторические приключения',
            'adv_indian' => 'Приключения про индейцев',
            'adv_maritime' => 'Морские приключения',
            'adv_geo' => 'Путешествия и география',
            'adv_animal' => 'Природа и животные',
        ],
        'antique' => [
            'antique_ant' => 'Античная литература',
            'antique_european' => 'Европейская старинная литература',
            'antique_russian' => 'Древнерусская литература',
            'antique_east' => 'Древневосточная литература',
            'antique_myths' => 'Мифы. Легенды. Эпос',
        ],
        'children' => [
            'child_tale' => 'Сказка',
            'child_verse' => 'Детские стихи',
            'child_prose' => 'Детскиая проза',
            'child_sf' => 'Детская фантастика',
            'child_det' => 'Детские остросюжетные',
            'child_adv' => 'Детские приключения',
            'child_education' => 'Детская образовательная литература',
        ],
        'computers' => [
            'comp' => [
                'comp_www' => 'Интернет',
                'comp_programming' => 'Программирование',
                'comp_hard' => 'Компьютерное "железо" (аппаратное обеспечение)',
                'comp_soft' => 'Программы',
                'comp_db' => 'Базы данных',
                'comp_osnet' => 'ОС и Сети',
            ],
        ],
        'detective' => [
            'det_action' => 'Боевик',
            'det_classic' => 'Классический детектив',
            'det_crime' => 'Криминальный детектив',
            'det_espionage' => 'Шпионский детектив',
            'det_hard' => 'Крутой детектив',
            'det_history' => 'Исторический детектив',
            'det_irony' => 'Иронический детектив',
            'det_maniac' => 'Маньяки',
            'det_police' => 'Полицейский детектив',
            'det_political' => 'Политический детектив',
        ],
//        'economics_*'        => 'Экономика',
        'home' => [
            'home_cooking' => 'Кулинария',
            'home_pets' => 'Домашние животные',
            'home_crafts' => 'Хобби и ремесла',
            'home_entertain' => 'Развлечения',
            'home_health' => 'Здоровье',
            'home_garden' => 'Сад и огород',
            'home_diy' => 'Сделай сам',
            'home_sport' => 'Спорт',
            'home_sex' => 'Эротика, Секс',
        ],
        'humor' => [
            'humor_anecdote' => 'Анекдоты',
            'humor_prose' => 'Юмористическая проза',
            'humor_verse' => 'Юмористические стихи',
        ],
        'love' => [
            'love_contemporary' => 'Современные любовные романы',
            'love_history' => 'Исторические любовные романы',
            'love_detective' => 'Остросюжетные любовные романы',
            'love_short' => 'Короткие любовные романы',
            'love_erotica' => 'Эротика',
        ],
//        'military_*'         => 'Военная литература',
        'nonfiction' => [
            'nonf_biography' => 'Биографии и Мемуары',
            'nonf_criticism' => 'Критика',
            'nonf_military' => 'Военная литература',
            'nonf_publicism' => 'Публицистика',
        ],
        'prose' => [
            'prose_classic' => 'Проза классическая',
            'prose_contemporary' => 'Современная проза',
            'prose_history' => 'Историческая проза',
            'prose_rus_classic' => 'Русская классическая проза',
            'prose_su_classics' => 'Советская классическая проза',
        ],
        'reference' => [
            'ref_encyc' => 'Энциклопедии',
            'ref_dict' => 'Словари',
            'ref_ref' => 'Справочники',
            'ref_guide' => 'Руководства',
        ],
        'religion' => [
            'religion_rel' => 'Религия',
            'religion_esoterics' => 'Эзотерика',
            'religion_self' => 'Самосовершенствование',
        ],
        'science' => [
            'sci_biology' => 'Биология',
            'sci_business' => 'Деловая литература',
            'sci_chem' => 'Химия',
            'sci_culture' => 'Культурология',
            'sci_history' => 'История',
            'sci_juris' => 'Юриспруденция',
            'sci_linguistic' => 'Языкознание',
            'sci_math' => 'Математика',
            'sci_medicine' => 'Медицина',
            'sci_philosophy' => 'Философия',
            'sci_phys' => 'Физика',
            'sci_politics' => 'Политика',
            'sci_psychology' => 'Психология',
            'sci_religion' => 'Религиоведение',
            'sci_tech' => 'Технические науки',
        ],
        'sf' => [
            'sf_action' => 'Боевая фантастика',
            'sf_cyberpunk' => 'Киберпанк',
            'sf_detective' => 'Детективная фантастика',
            'sf_epic' => 'Эпическая фантастика',
            'sf_fantasy_city' => 'Городская фэнтези',
            'sf_fantasy' => 'Фэнтези',
            'sf_heroic' => 'Героическая фантастика',
            'sf_history' => 'Альтернативная история',
            'sf_horror' => 'Ужасы и Мистика',
            'sf_humor' => 'Юмористическая фантастика',
            'sf_social' => 'Социально-психологическая фантастика',
            'sf_space' => 'Космическая фантастика',
        ],
    ];

    public static function getGenresGroup(): array
    {
        $ret = [];
        foreach (self::$typeGenreNames as $nameEn => $titleRu) {
            $ret[$nameEn] = $titleRu;
        }
        return $ret;
    }

    public static function getGenresOfGroup($group): array
    {
        $ret = [];
        if (array_key_exists($group, self::$typeGenreNames)) {
            $ret[$group] = self::$typeGenreNames[$group];
            if (array_key_exists($group, self::$genreNames)) {
                foreach (self::$genreNames[$group] as $nameEn => $titleRu) {
                    $ret[$nameEn] = $titleRu;
                }
            }
        }
        return $ret;
    }

    public static function getNewGenre($genre)
    {
        if (array_key_exists($genre, self::$FB20_FB21_recoding)) {
            return self::$FB20_FB21_recoding[$genre];
        } elseif (array_key_exists($genre, self::$typeGenreNames)) {
            return $genre;
        } else {
            foreach (self::$genreNames as $type) {
                if (array_key_exists($genre, $type)) {
                    return $genre;
                }
            }
        }
        return null;
    }

    public static function getGenreTitle($genre)
    {
        $nGenre = self::getNewGenre($genre);
        if ($nGenre) {
            if (array_key_exists($nGenre, self::$typeGenreNames)) {
                return self::$typeGenreNames[$nGenre];
            } else {
                foreach (self::$genreNames as $type) {
                    if (array_key_exists($genre, $type)) {
                        return $type[$genre];
                    }
                }
            }
        }
        return null;
    }

}