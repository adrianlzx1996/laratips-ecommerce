<script setup>

defineProps({
    headers: {
        type: Array,
        default: () => [],
    },
    items: {
        type: Object,
        default: () => ({}),
    },
})
</script>

<template>
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="border-b">
                    <tr>
                        <Th v-for="header in headers" :key="header.label">{{ header.label }}</Th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in items.data" :key="item.id" class="border-b">
                        <slot :item="item"/>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import Th from "./Th.vue";

export default {
    name: "Table",
    components: {Th}
}
</script>

<style scoped>

</style>
