<div class="bg-gradient-to-r from-cyan-200 via-teal-300 to-zinc-200 py-6 sm:py-8 lg:py-12">
    <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
      <h2 class="mb-8 text-center text-2xl font-bold text-gray-800 md:mb-12 lg:text-3xl">Program Unggulan</h2>

      <div class="grid gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-3 xl:grid-cols-3">
        @foreach ($unggulan as $item)
        <!-- product - start -->
        <div>
          <a href="#" class="group relative flex h-96 items-end overflow-hidden rounded-lg bg-gray-100 p-4 shadow-lg">
            <img src="{{ asset('storage/' . $item->picture) }}" loading="lazy" alt="{{$item->name}}" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />

            <div class="relative flex w-full flex-col rounded-lg bg-white p-4 text-center">
              <span class="text-lg font-bold text-gray-800 lg:text-xl">{{$item->name}}</span>
            </div>
          </a>
        </div>
        <!-- product - end -->
        @endforeach
      </div>
    </div>
  </div>
