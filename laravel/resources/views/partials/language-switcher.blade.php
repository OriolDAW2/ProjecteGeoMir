<div class="dropdown">
   <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
       {{ $availableLocales[$currentLocale] }} ({{ $currentLocale }})
   </a>
   <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
   @foreach($availableLocales as $locale => $localeName)
       @if($locale !== $currentLocale)
           <li><a class="dropdown-item" href="{{ url('language/'.$locale) }}">{{ $localeName }} ({{ $locale }})</a></li>
       @endif
   @endforeach
   </ul>       
</div>
<script>
   const currentLocale = {{ Js::from($currentLocale) }};
</script>