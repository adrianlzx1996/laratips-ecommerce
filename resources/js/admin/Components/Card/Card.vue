<script setup>
defineProps({
    isLoading: {
        type: Boolean,
        default: false
    }
})

</script>
<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b border-gray-200">
        <div v-show="isLoading" class="animate-progress w-full bg-gray-200 h-1">
            <div class="bg-blue-600 h-1"></div>
        </div>

        <div v-if="$slots.header" class="px-6 py-2 font-bold bg-gray-50">
            <slot name="header"></slot>
        </div>
        <div class="p-6">
            <slot/>
        </div>
    </div>
</template>

<script>
export default {
    name: "Card"
}
</script>

<style scoped>

</style>
