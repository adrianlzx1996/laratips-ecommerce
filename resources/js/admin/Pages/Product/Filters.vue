<script setup>
import Card from '@/Components/Card/Card.vue';
import InputGroup from '@/Components/InputGroup.vue';
import { ref, watch } from "vue";
import SelectGroup from "../../Components/SelectGroup.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({}),
    },
    categories: Array
})

const emits = defineEmits(["update:modelValue"])

const filters = ref({...props.modelValue})

watch(filters, (value) => {
    emits("update:modelValue", filters.value)
}, {
    deep: true
})
</script>

<template>
    <Card class="mb-4">
        <template #header>Filters</template>
        <form class="grid grid-cols-4 gap-8">
            <InputGroup v-model="filters.name" label="Name"/>
            <SelectGroup items="categories" label="Category"/>
        </form>
    </Card>
</template>

<script>

export default {
    name: "Filters"
}
</script>

<style scoped>

</style>
