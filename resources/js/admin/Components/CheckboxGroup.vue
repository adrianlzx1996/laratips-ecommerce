<script setup>
import Checkbox from "./Checkbox.vue";
import InputError from "./InputError.vue";

defineProps({
    value: {
        type: Object,
        default: () => ({}),
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
        <Label>
            <Checkbox :value="value"
                      v-bind="$attrs" @update:checked="$emit('update:checked', $event)"/>
            {{ label }}
        </Label>
        <InputError v-if="errorMessage" :message="errorMessage" class="mt-1"/>
    </div>
</template>

<script>
import Label from "./Label.vue";

export default {
    name: "CheckboxGroup",
    components: {Label}
}
</script>

<style scoped>

</style>
