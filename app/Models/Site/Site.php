<?php

namespace App\Models\Site;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Site.
 *
 * @property int $id
 * @property string $domain 域名
 * @property bool $online 是否在线
 * @property string path 接口路径
 * @property int $seo 百度收录量
 * @property string $get_type 爬虫或者 API
 * @property string $date_xpath 最新日期的 xpath
 * @property string $keyword 关键字，- 或 + 开头
 * @property string $date_format 最新日期的格式
 * @property \Illuminate\Support\Carbon|string|null $last_updated_at 站点最后更新时间
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|array<\App\Models\Site\Check> $history
 * @property-read int|null $history_count
 * @property-read \App\Models\Site\Check|null $todayLatest
 * @property-read \App\Models\Site\Check|null $todayLatestWithFailed
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Site newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Site newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Site query()
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereDateFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereDateXpath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereGetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Site extends Model
{
    public function history(): HasMany
    {
        return $this->hasMany(Check::class);
    }

    /**
     * @return Attribute
     */
//    protected function lastUpdatedAt(): Attribute
//    {
//        return new Attribute(
//            get: function () {
//                if (is_null($this->attributes['last_updated_at'])) {
//                    return '更新信息错误';
//                }
//
//                return Carbon::parse($this->attributes['last_updated_at'])->diffForHumans();
//            }
//        );
//    }

    public function todayLatest(): HasOne
    {
        return $this->hasOne(Check::class)->whereDate('created_at', Carbon::today())->latest();
    }

    public function todayLatestWithFailed(): HasOne
    {
        return $this->hasOne(Check::class)->whereDate('created_at', Carbon::today())->latest();
    }

    public function scopeFailed($query)
    {
        return $query
            ->whereNotNull('get_type')
            ->where('is_online', 0)
            ->orWhere('is_new', 0);
    }
}