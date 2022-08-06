<script setup>

import Label from '@/Components/Label.vue';
import Select from "./Select.vue";
import InputError from "./InputError.vue";

const props = defineProps({
    modelValue: {},
    type: {
        type: String,
        default: 'text',
    },
    label: {
        type: String,
        default: '',
    },
    errorMessage: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div>
        <Label v-if="label" :value="label"/>

        <Select :model-value="modelValue" class="mt-1" v-bind="$attrs"
                @update:model-value="$emit('update:modelValue', $event)"/>

        <InputError v-if="errorMessage" :message="errorMessage" class="mt-1"/>
    </div>
</template>

<script>
export default {
    name: "SelectGroup"
}
</script>

<style scoped>

</style>
