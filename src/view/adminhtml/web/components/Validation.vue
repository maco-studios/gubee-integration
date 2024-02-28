<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-9999" @close="open = false">
            <div class="fixed inset-0" />

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700"
                            enter-from="translate-x-full" enter-to="translate-x-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0"
                            leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto w-screen max-w-md">
                                <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                                    <div class="px-4 sm:px-6">
                                        <div class="flex items-start justify-between">
                                            <DialogTitle class=" font-semibold leading-6 text-gray-900">Gubee
                                                Integration Validation Panel
                                            </DialogTitle>
                                            <div class="ml-3 flex h-7 items-center">
                                                <button type="button"
                                                    class="relative rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                                    @click="open = false">
                                                    <span class="absolute -inset-2.5" />
                                                    <span class="sr-only">Close panel</span>
                                                    <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative mt-6 flex-1 px-4 sm:px-6">
                                        <div v-for="(error, index) in errors"
                                            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                            <div class="p-4">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <ExclamationTriangleIcon class="h-6 w-6 text-orange-400"
                                                            aria-hidden="true" />
                                                    </div>
                                                    <div class="ml-3 w-0 flex-1 pt-0.5">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ error }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import { ref } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon, CheckCircleIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

export default {
    components: {
        ExclamationTriangleIcon,
        CheckCircleIcon,
        Dialog,
        DialogPanel,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        XMarkIcon,
    },
    setup() {
        const open = ref(false)
        return {
            open,
        }
    },
    props: {
        errors: {
            type: Object,
            default: () => { },
        },
    },
    mounted() {
        document.querySelector('#gubee_validation').addEventListener('click', () => {
            this.open = !this.open
        })
    },
}

</script>

<style>
.z-9999 {
    z-index: 9999;
}
</style>
