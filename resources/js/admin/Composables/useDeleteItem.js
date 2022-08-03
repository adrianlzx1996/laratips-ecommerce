import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

export default function ({routeResourceName}) {
    const deleteModal = ref(false);
    const itemToDelete = ref({})
    const isDeleting = ref(false);
    const showDeleteModal = (item) => {
        deleteModal.value = true;
        itemToDelete.value = item;
    }

    const handleDeleteItem = () => {
        Inertia.delete(route(`admin.${routeResourceName}.destroy`, {id: itemToDelete.value.id}), {
            onBefore: () => {
                isDeleting.value = true;
            },
            onSuccess: () => {
                deleteModal.value = false;
                itemToDelete.value = {};
            },
            onFinish: () => {
                isDeleting.value = false;
            }
        })
    }

    return {
        deleteModal, itemToDelete, isDeleting, showDeleteModal, handleDeleteItem
    }
}