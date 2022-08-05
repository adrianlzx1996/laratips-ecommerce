<script setup>
import Card from '@/Components/Card/Card.vue';
import Label from '@/Components/Label.vue';
import Input from '@/Components/Input.vue';
import { ref, watch } from "vue";

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({}),
    },
})

const emits = defineEmits(["update:modelValue"])

const filters = ref({...props.modelValue})

watch(filters, (value) => {
    emits("update:modelValue", filters.value)
})
</script>

<template>
    <Card class="mb-4">
        <template #header>Filters</template>
        <form class="grid grid-cols-4 gap-8">
            <div>
                <Label value="Name"/>
                <Input v-model="modelValue.name" class="mt-1 block w-full" type="text"/>
            </div>
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
