<?php

namespace App\Services;

class SeoService
{
    protected array $data = [];

    public function __construct()
    {
        $this->data = [
            'title'        => config('seo.default_title'),
            'description'  => config('seo.default_description'),
            'keywords'     => config('seo.default_keywords'),
            'canonical'    => request()->url(),
            'og_image'     => config('seo.default_og_image') ?: url('/og-image.png'),
            'og_type'      => 'website',
            'robots'       => 'index, follow',
            'schema'       => [],
            'breadcrumbs'  => [],
        ];
    }

    public function title(string $t): static   { $this->data['title'] = $t . ' | ' . config('seo.site_name', 'MyRoom'); return $this; }
    public function description(string $d): static { $this->data['description'] = mb_substr(strip_tags($d), 0, 160); return $this; }
    public function keywords(string|array $k): static { $this->data['keywords'] = is_array($k) ? implode(', ', $k) : $k; return $this; }
    public function canonical(string $u): static { $this->data['canonical'] = $u; return $this; }
    public function ogImage(string $u): static  { $this->data['og_image'] = $u; return $this; }
    public function ogType(string $t): static   { $this->data['og_type'] = $t; return $this; }
    public function robots(string $r): static   { $this->data['robots'] = $r; return $this; }
    public function noIndex(): static           { return $this->robots('noindex, nofollow'); }
    public function breadcrumbs(array $c): static { $this->data['breadcrumbs'] = $c; return $this; }
    public function addSchema(array $s): static { $this->data['schema'][] = $s; return $this; }

    public function schemaWebsite(): static {
        return $this->addSchema(['@context'=>'https://schema.org','@type'=>'WebSite','name'=>'MyRoom','url'=>config('app.url'),'potentialAction'=>['@type'=>'SearchAction','target'=>['@type'=>'EntryPoint','urlTemplate'=>config('app.url').'/search?city={search_term_string}'],'query-input'=>'required name=search_term_string']]);
    }

    public function schemaOrganization(): static {
        return $this->addSchema(['@context'=>'https://schema.org','@type'=>'Organization','name'=>'MyRoom','url'=>config('app.url'),'contactPoint'=>['@type'=>'ContactPoint','telephone'=>'+91-9876543210','contactType'=>'customer service']]);
    }

    public function schemaFaq(array $faqs): static {
        return $this->addSchema(['@context'=>'https://schema.org','@type'=>'FAQPage','mainEntity'=>array_map(fn($f)=>['@type'=>'Question','name'=>$f['q'],'acceptedAnswer'=>['@type'=>'Answer','text'=>$f['a']]],$faqs)]);
    }

    public function schemaHotel(array $data): static {
        return $this->addSchema(array_merge(['@context'=>'https://schema.org','@type'=>'LodgingBusiness'],$data));
    }

    public function schemaBreadcrumbs(array $crumbs): static {
        $items = array_map(fn($c,$i)=>['@type'=>'ListItem','position'=>$i+1,'name'=>$c['name'],'item'=>$c['url']??''],array_values($crumbs),array_keys($crumbs));
        return $this->addSchema(['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>$items]);
    }

    public function get(string $k, mixed $d = null): mixed { return $this->data[$k] ?? $d; }
    public function all(): array { return $this->data; }

    public function renderSchemas(): string {
        if (empty($this->data['schema'])) return '';
        return collect($this->data['schema'])
            ->map(fn($s) => '<script type="application/ld+json">'.json_encode($s, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES).'</script>')
            ->implode("\n");
    }
}
