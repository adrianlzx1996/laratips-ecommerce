<script setup>
import { onMounted, ref, watch } from 'vue';
import { usePage } from "@inertiajs/inertia-vue3";

const message = ref(usePage().props.value?.flash?.success)

watch(() => usePage().props.value?.flash?.success,
    (successMessage) => message.value = successMessage, {
        immediate: true
    })

onMounted(() => {
    setTimeout(() => {
        message.value = ""
    }, 5000)
});
</script>
<template>
    <div v-if="message" class="bg-green-600 text-white rounded fixed right-0 top-0 px-4 py-2 mr-4 mt-4 z-10">
        {{ message }}
    </div>
</template>

<script>
export default {
    name: "Alert"
}
</script>

<style scoped>

</style>
