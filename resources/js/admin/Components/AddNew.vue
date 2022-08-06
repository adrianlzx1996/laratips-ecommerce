<script setup>
import Button from "@/Components/Button.vue";
import Filter from "@/Components/Icons/Filter.vue";
import { ref } from "vue";

const showFilter = ref(false)
</script>

<template>
    <div>
        <div class="flex items-center justify-between">
            <slot/>

            <Button v-if="$slots.filter" color="white" @click="showFilter = !showFilter">
                <Filter class="w-4 h-4"/>
            </Button>
        </div>

        <slot v-if="showFilter" name="filter"/>
    </div>
</template>

<script>
export default {
    name: "AddNew"
}
</script>

<style scoped>

</style>
