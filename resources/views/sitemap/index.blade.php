<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>{{ url('/') }}</loc><changefreq>daily</changefreq><priority>1.0</priority></url>
  <url><loc>{{ route('search') }}</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
  <url><loc>{{ route('cities') }}</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
  @foreach($cities as $city)
    <url><loc>{{ route('search.city',strtolower($city)) }}</loc><changefreq>daily</changefreq><priority>0.8</priority></url>
  @endforeach
  @foreach($hotels as $h)
    <url><loc>{{ route('hotel.show',$h->slug) }}</loc><lastmod>{{ $h->updated_at->toAtomString() }}</lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>
  @endforeach
</urlset>
