<script setup>

import { computed, onMounted, ref } from "vue";

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
});

defineEmits(["update:modelValue"]);

const options = computed(() => {
    if (props.withoutSelect) return props.items;

    return [
        {
            [props.itemLabel]: ' - Select - ',
            [props.itemValue]: '',
        },
        ...props.items,
    ]
})

const select = ref(null);

onMounted(() => {
    if (select.value.hasAttribute("autofocus")) {
        select.value.focus();
    }
});
</script>

<template>
    <select ref="select" :value="modelValue"
            class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full block"
            @input="$emit('update:modelValue', $event.target.value)">
        <option v-for="item in options" :key="item[itemValue]" :value="item[itemValue]">
            {{ item[itemLabel] }}
        </option>
    </select>
</template>

<script>
export default {
    name: "Select"
}
</script>

<style scoped>

</style>
