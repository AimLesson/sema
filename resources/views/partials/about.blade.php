<div class="bg-gradient-to-r from-teal-300 to-sky-500 py-6 sm:py-8 lg:py-12">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
      <div class="grid gap-8 md:grid-cols-2 lg:gap-12">
        <div>
          <div class="h-48 overflow-hidden md:h-72">
            <img src="{{ asset('storage/' . $profile->logo) }}" loading="lazy" alt="Photo by Martin Sanchez" class="h-full w-full object-contain object-center" />
          </div>
        </div>

        <div class="md:pt-2">
          <h1 class="mb-4 text-center text-xl font-bold text-gray-800 sm:text-2xl md:mb-3 md:text-left">Tentang Kami</h1>
          <div class="text-justify text-sm md:text-lg text-gray-900">
            {!! str($profile->description)->sanitizeHtml() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
