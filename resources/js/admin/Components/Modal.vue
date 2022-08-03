<script setup>

import { computed, onMounted, onUnmounted, watch } from "vue";

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        required: false
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
})

const emits = defineEmits(['update:modelValue']);

const id = 'modal-' + parseInt(Math.random() * 1000000).toString();

const modalIdElement = computed(() => {
    return document.getElementById(id);
});

watch(() => props.modelValue,
    value => {
        if (value) {
            window.Modal.getOrCreateInstance(modalIdElement.value).show();
        } else {
            window.Modal.getInstance(modalIdElement.value).hide();
        }
    })

const emitOpenModalEvent = () => {
    emits('update:modelValue', true);
}

const emitCloseModalEvent = () => {
    emits('update:modelValue', false);
}

onMounted(() => {
    modalIdElement.value.addEventListener("hidden.bs.modal", emitCloseModalEvent);
    modalIdElement.value.addEventListener("show.bs.modal", emitOpenModalEvent);
})

onUnmounted(() => {
    modalIdElement.value.removeEventListener("hidden.bs.modal", emitCloseModalEvent);
    modalIdElement.value.removeEventListener("show.bs.modal", emitOpenModalEvent);
})

const modalSizeClass = computed(() => `modal-${props.size}`)
</script>

<template>
    <div :id="id"
         :aria-labelledby="id + 'label'" aria-modal="true"
         class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" role="dialog"
         tabindex="-1">
        <div :class="modalSizeClass"
             class="modal-dialog modal-dialog-centered modal-dialog-scrollable relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div
                    class="modal-header flex flex-shrink-0 items-center justify-between p-4 border-b border-gray-200 rounded-t-md">
                    <h5 :id="id + 'Label'"
                        class="text-xl font-medium leading-normal text-gray-800">
                        {{ title }}
                    </h5>
                    <button aria-label="Close"
                            class="btn-close box-content w-4 h-4 p-1 text-black border-none rounded-none opacity-50 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                            data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body relative p-4">
                    <slot/>
                </div>
                <div
                    v-if="$slots.footer"
                    class="modal-footer flex flex-shrink-0 flex-wrap items-center justify-end p-4 border-t border-gray-200 rounded-b-md">
                    <slot name="footer"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Modal"
}
</script>

<style scoped>

</style>
