<script setup>
import { Link } from '@inertiajs/inertia-vue3'

defineProps({
    editLink: {
        type: String,
        default: () => "",
    },
    showEditLink: {
        type: Boolean,
        default: () => true,
    },
    showDeleteLink: {
        type: Boolean,
        default: () => true,
    },
});
</script>
<template>
    <div class="flex items-center space-x-2">
        <button v-if="showEditLink">
            <Link :href="editLink">
                <Edit class="w-4 h-4"></Edit>
            </Link>
        </button>
        <button v-if="showDeleteLink">
            <Delete class="w-4 h-4"></Delete>
        </button>
    </div>
</template>

<script>
import Edit from "../Icons/Edit.vue";
import Delete from "../Icons/Delete.vue";

export default {
    name: "Actions",
    components: {Edit, Delete},
}
</script>

<style scoped>

</style>
