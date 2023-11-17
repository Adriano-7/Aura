<div class="flex flex-nowrap lg:ml-10 md:ml-20 ml-10 ">
    <div class=" bg-gray-960 rounded-lg shadow dark:border-gray-850 hover:shadow-lg card-aura">
        <a href="#">
            <img class="rounded-t-lg w-full h-48 object-cover" src="{{ asset('images/eventos/guns.png') }}" alt="" />
            <div class="p-3">
                <h5 class="mb-2 text-base font-bold tracking-tight text-white dark:text-white text-center">
                    {{ $event->name }}
                </h5>
                <p class="mb-3  text-xs font-normal text-white-700 dark:text-gray-400 text-center">
                    {{ $event->start_date->format('d M Y') }} <br>
                    {{ $event->location }}
                </p>
            </div>
        </a>
    </div>
</div>
