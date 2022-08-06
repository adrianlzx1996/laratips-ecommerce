<script setup>

import Label from '@/Components/Label.vue';
import Select from "./Select.vue";
import InputError from "./InputError.vue";

const props = defineProps({
    modelValue: {},
    items: Array,
    itemLabel: {
        type: String,
        default: 'name',
    },
    itemValue: {
        type: String,
        default: 'id',
    },
    withoutSelect: {
        type: Boolean,
        default: false,
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

        <Select :item-label="itemLabel" :item-value="itemValue" :items="items" :model-value="modelValue"
                :without-select="withoutSelect" class="mt-1"
                v-bind="$attrs" @update:model-value="$emit('update:modelValue', $event)"/>

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
