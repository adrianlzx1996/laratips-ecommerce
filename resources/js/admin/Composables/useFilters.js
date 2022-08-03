import { onMounted, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";

export default function (params) {
    const {filters: defaultFilters, routeResourceName} = params;

    const filters = ref({
        name: ''
    })

    const fetchItemsHandler = ref(null);
    const fetchItems = () => {
        Inertia.get(route(`admin.${routeResourceName}.index`), filters.value, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    onMounted(() => {
        filters.value = defaultFilters;
    })

    watch(filters, () => {
        clearTimeout(fetchItemsHandler.value);

        fetchItemsHandler.value = setTimeout(() => {
            fetchItems();
        }, 300)
    }, {
        deep: true
    })

    return {
        filters
    }
}