<?php
/**
 * Created by PhpStorm.
 * User: lutov
 * Date: 04.03.2018
 * Time: 10:48
 */

namespace App\Helpers;

use App\Models\Data\Collection;
use App\Models\Data\Country;
use App\Models\Data\Genre;
use App\Models\Data\Platform;
use App\Models\Data\Section;
use App\Models\Search\ElementGenre;
use App\Models\User\Rate;
use App\Models\User\Unwanted;
use App\Models\User\User;
use App\Models\User\Wanted;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use ResizeCrop;

class ElementsHelper
{

    /**
     * @param  string  $slug
     * @param  string  $name
     * @param  int  $count
     * @param  Section  $section
     * @param $elements
     * @return array
     */
    public static function tab(string $slug, string $name, int $count, Section $section, $elements)
    {
       return array(
           'slug' => $slug,
           'name' => $name,
           'count' => $count,
           'section' => $section,
           'elements' => $elements,
       );
    }

    /**
     * @param $element
     * @return array
     */
    public static function countRating($element)
    {
        $rates = $element->rates;
        $rates_count = $rates->count('rate');
        $rates_sum = $rates->sum('rate');
        $rating = array();
        if (0 != $rates_count) {
            $rating['average'] = round($rates_sum / $rates_count, 2);
            $rating['count'] = $rates_count;
        } else {
            $rating['average'] = 0;
            $rating['count'] = 0;
        }
        return $rating;
    }

    /**
     * @param  Request  $request
     * @param $section
     * @return string|null
     */
    public static function getRecommend(Request $request, $section)
    {
        $result = null;
        $type = SectionsHelper::getSectionType($section);
        $minutes = 60;
        $var_name = 'collection_'.$section.'';
        $rows = Cache::remember(
            $var_name,
            $minutes,
            function () use ($type) {
                return Rate::where('element_type', '=', $type)
                    ->where('user_id', '=', 1)
                    ->where('rate', '>', 6)
                    ->get();
            }
        );
        if (!$rows->count()) {
            return '';
        }
        $rand_row = rand(0, $rows->count());
        if (isset($rows[$rand_row]) && !empty($rows[$rand_row])) {
            $element_id = $rows[$rand_row]->element_id;
            $element = $type::find($element_id);
            if (!empty($element)) {
                $result = $element;
            } else {
                $result = self::getRecommend($request, $section);
            }
        }
        return $result;
    }

    /**
     * @param  array  $options
     * @return array
     */
    public static function getSimilar(array $options = array())
    {
        $sim_elem = [];
        if (count($options)) {
            $rand_id = DB::table('elements_genres')
                ->orderBy(DB::raw('RAND()'))
                ->where('element_type', '=', $options['type'])
                ->where(
                    function ($query) use ($options) {
                        foreach ($options['genres'] as $key => $value) {
                            $query->orWhere('genre_id', '=', $value->id);
                        }
                    }
                )
                ->value('element_id');
            $sim_elem = $options['type']::find($rand_id);
        }
        if (empty($sim_elem)
            || (0 == $sim_elem->verified)
            || ($options['element_id'] == $sim_elem->id)
        ) {
            $sim_elem = self::getSimilar($options);
        }
        return $sim_elem;
    }

    /**
     * @param  int  $id
     * @param  string  $section
     * @param  string  $type
     */
    public static function deleteElement(int $id = 0, string $section = '', string $type = '')
    {
        Rate::where('element_id', '=', $id)
            ->where('element_type', '=', $type)
            ->delete();
        Wanted::where('element_id', '=', $id)
            ->where('element_type', '=', $type)
            ->delete();
        Unwanted::where('element_id', '=', $id)
            ->where('element_type', '=', $type)
            ->delete();
        ElementGenre::where('element_id', '=', $id)
            ->where('element_type', '=', $type)
            ->delete();
        self::deleteCover($section, $id);
        $type::find($id)->delete();
    }

    /**
     * @param  Section  $section
     * @param  int  $donor_id
     * @param  int  $recipient_id
     */
    public static function transfer(Section $section, int $donor_id, int $recipient_id)
    {
        $rates = Rate::where('element_type', '=', $section->type)
            ->where('element_id', '=', $recipient_id)
            ->pluck('id')
            ->toArray();

        Rate::where('element_type', '=', $section->type)
            ->where('element_id', '=', $donor_id)
            ->whereNotIn('id', $rates)
            ->update(array('element_id' => $recipient_id));

        self::deleteElement($donor_id, $section->alt_name, $section->type);
    }

    /**
     * @param  string  $section
     * @param  bool  $cache
     * @return mixed
     */
    public static function getGenres(string $section, bool $cache = true)
    {
        $minutes = 60;
        $var_name = $section.'_genres';
        $type = SectionsHelper::getSectionType($section);
        if ($cache) {
            $result = Cache::remember(
                $var_name,
                $minutes,
                function () use ($type) {
                    return Genre::where('element_type', $type)->orderBy('name')->get();
                }
            );
        } else {
            $result = Genre::where('element_type', $type)->orderBy('name')->get();
        }
        return $result;
    }

    /**
     * @param  bool  $cache
     * @return mixed
     */
    public static function getCollections(bool $cache = true)
    {
        if ($cache) {
            $minutes = 60;
            $var_name = 'collections';
            $result = Cache::remember(
                $var_name,
                $minutes,
                function () {
                    return Collection::orderBy('name')->get();
                }
            );
        } else {
            $result = Collection::orderBy('name')->get();
        }
        return $result;
    }

    /**
     * @param  bool  $cache
     * @return mixed
     */
    public static function getCountries(bool $cache = true)
    {
        if ($cache) {
            $minutes = 60;
            $var_name = 'countries';
            $result = Cache::remember(
                $var_name,
                $minutes,
                function () {
                    return Country::orderBy('name')->get();
                }
            );
        } else {
            $result = Country::orderBy('name')->get();
        }
        return $result;
    }

    /**
     * @param  bool  $cache
     * @return mixed
     */
    public static function getPlatforms(bool $cache = true)
    {
        if ($cache) {
            $minutes = 60;
            $var_name = 'platforms';
            $result = Cache::remember(
                $var_name,
                $minutes,
                function () {
                    return Platform::orderBy('name')->get();
                }
            );
        } else {
            $result = Platform::orderBy('name')->get();
        }
        return $result;
    }

    /**
     * @param  Request  $request
     * @param  string  $section
     * @param  int  $element_id
     */
    public static function setCover(Request $request, string $section, int $element_id)
    {
        $covers_path = '/data/img/covers/';
        $extension = '.webp';
        $path = public_path().$covers_path.$section;
        $fileName = $element_id.$extension;
        $full_path = $path.'/'.$fileName;
        if ($request->hasFile('cover')) {
            if (file_exists($full_path)) {
                unlink($full_path);
            }
            self::resizeCrop($request->file('cover')->getRealPath(), $full_path);
        }
    }

    /**
     * @param  string  $section
     * @param  int  $id
     * @return int
     */
    public static function getCover(string $section, int $id)
    {
        $covers_path = '/data/img/covers/';
        $extension = '.webp';
        $rel_path = $covers_path.$section.'/'.$id.$extension;
        $file_path = public_path().$rel_path;
        if (file_exists($file_path)) {
            $hash = md5_file($file_path);
            $cover = $rel_path.'?hash='.$hash;
        } else {
            $rel_path = $covers_path.$section.'/0'.$extension;
            $file_path = public_path().$rel_path;
            $hash = md5_file($file_path);
            $cover = $rel_path.'?hash='.$hash;
        }
        return $cover;
    }

    /**
     * @param  string  $section
     * @param  int  $id
     */
    public static function deleteCover(string $section, int $id)
    {
        $covers_path = '/data/img/covers/';
        $extension = '.webp';
        $rel_path = $covers_path.$section.'/'.$id.$extension;
        $file_path = public_path().$rel_path;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }


    /**
     * @param $real_path
     * @param $full_path
     */
    private static function resizeCrop($real_path, $full_path)
    {
        $width = 185 * 2;
        $height = 270 * 2;

        $resize = ResizeCrop::resize($real_path, $full_path, $width, 0);
        $size = getimagesize($full_path);
        /*
        if($height > $size[1]) {
            $diff = ($height - $size[1]) / 2;
            $crop = ResizeCrop::crop($full_path, $full_path, array(0, -$diff, $width, ($height - $diff)));
        }
        */
    }

    /**
     * @param $element
     * @param $user
     * @return int
     */
    public static function getRate($element, $user)
    {
        $rate = 0;
        if($element->rates) {
            $user_rate = $element->rates
                ->where('user_id', $user->id)
                ->first();
            if (isset($user_rate->rate)) {
                $rate = $user_rate->rate;
            }
        }
        return $rate;
    }

    /**
     * @param $element
     * @param  User|Authenticatable  $user
     * @return mixed
     */
    public static function isWanted($element, User $user)
    {
        if (method_exists($element, 'wanted')) {
            return $element->wanted
                ->where('user_id', $user->id)
                ->first();
        } else {
            return false;
        }
    }

    /**
     * @param $element
     * @param  User|Authenticatable  $user
     * @return mixed
     */
    public static function isUnwanted($element, User $user)
    {
        if (method_exists($element, 'wanted')) {
            return $element->unwanted
                ->where('user_id', $user->id)
                ->first();
        } else {
            return false;
        }
    }

    /**
     * @param  Section  $section
     * @param  int  $user_id
     * @param  array  $cache
     * @return mixed
     */
    public static function getWanted(Section $section, int $user_id, array $cache = array())
    {
        if (count($cache)) {
            $minutes = $cache['minutes'];
            $var_name = $cache['name'].'_wanted_'.$section->alt_name;
            $wanted = Cache::remember(
                $var_name,
                $minutes,
                function () use ($section, $user_id) {
                    return Wanted::select('element_id')
                        ->where('element_type', '=', $section->type)
                        ->where('user_id', '=', $user_id)
                        ->pluck('element_id')
                        ->toArray();
                }
            );
        } else {
            $wanted = Wanted::select('element_id')
                ->where('element_type', '=', $section->type)
                ->where('user_id', '=', $user_id)
                ->pluck('element_id')
                ->toArray();
        }
        return $wanted;
    }

    /**
     * @param  Section  $section
     * @param  int  $user_id
     * @param  array  $cache
     * @return mixed
     */
    public static function getUnwanted(Section $section, int $user_id, array $cache = array())
    {
        if (count($cache)) {
            $minutes = $cache['minutes'];
            $var_name = $cache['name'].'_unwanted_'.$section->alt_name;
            $unwanted = Cache::remember(
                $var_name,
                $minutes,
                function () use ($section, $user_id) {
                    return Unwanted::select('element_id')
                        ->where('element_type', '=', $section->type)
                        ->where('user_id', '=', $user_id)
                        ->pluck('element_id')
                        ->toArray();
                }
            );
        } else {
            $unwanted = Unwanted::select('element_id')
                ->where('element_type', '=', $section->type)
                ->where('user_id', '=', $user_id)
                ->pluck('element_id')
                ->toArray();
        }
        return $unwanted;
    }

}