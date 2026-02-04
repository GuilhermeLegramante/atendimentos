<x-filament-widgets::widget class="fi-filament-info-widget">

    <div x-data="{
        activeSlide: 0,
        notices: {{ $this->getNotices()->toJson() }},
        prev() {
            this.activeSlide = (this.activeSlide === 0) ? this.notices.length - 1 : this.activeSlide - 1;
        },
        next() {
            this.activeSlide = (this.activeSlide === this.notices.length - 1) ? 0 : this.activeSlide + 1;
        }
    }"
        class="
            w-full relative p-6 pl-10 rounded-xl border shadow-sm
            bg-white border-gray-200
            dark:bg-gray-900 dark:border-gray-700 dark:shadow-none
        ">

        <h2
            class="
                text-2xl font-bold mb-6 text-center
                text-blue-800
                dark:text-blue-400
            ">
            📢 Quadro de Avisos
        </h2>

        <div class="w-full max-w-4xl mx-auto text-center">
            <template x-for="(notice, index) in notices" :key="index">
                <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="space-y-4">
                    <h3 class="
                            text-xl font-semibold
                            text-blue-700
                            dark:text-blue-300
                        "
                        x-text="notice.title"></h3>

                    <p class="
                            text-md
                            text-gray-700
                            dark:text-gray-300
                        "
                        x-text="notice.message"></p>
                </div>
            </template>
        </div>

        <div class="flex justify-center items-center mt-8 gap-4">

            <button @click="prev"
                class="
                    font-medium py-2 px-4 rounded-lg transition
                    bg-blue-100 text-blue-800 hover:bg-blue-200
                    dark:bg-gray-800 dark:text-blue-300 dark:hover:bg-gray-700
                ">
                ⬅ Anterior
            </button>

            <div class="flex gap-2">
                <template x-for="(notice, index) in notices" :key="'dot' + index">
                    <div @click="activeSlide = index"
                        :class="activeSlide === index ?
                            'bg-blue-600 dark:bg-blue-400' :
                            'bg-blue-200 hover:bg-blue-400 dark:bg-gray-600 dark:hover:bg-gray-400'"
                        class="w-3 h-3 rounded-full transition cursor-pointer"></div>
                </template>
            </div>

            <button @click="next"
                class="
                    font-medium py-2 px-4 rounded-lg transition
                    bg-blue-100 text-blue-800 hover:bg-blue-200
                    dark:bg-gray-800 dark:text-blue-300 dark:hover:bg-gray-700
                ">
                Próximo ➡
            </button>

        </div>

    </div>

</x-filament-widgets::widget>
