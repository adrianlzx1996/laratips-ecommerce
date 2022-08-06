<script setup>
import Label from '@/Components/Label.vue';
import Input from '@/Components/Input.vue';
import InputError from '@/Components/InputError.vue';

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
})
</script>

<template>
    <div>
        <Label v-if="label" :value="label"/>
        <Input :model-value="modelValue" :type="type"
               class="mt-1 block w-full" v-bind="$attrs" @update:model-value="$emit('update:modelValue', $event)"/>

        <InputError v-if="errorMessage" :message="errorMessage" class="mt-1"/>
    </div>
</template>

<script>
export default {
    name: "InputGroup"
}
</script>

<style scoped>

</style>
