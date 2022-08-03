<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import Container from '@/Components/Container.vue';
import Card from '@/Components/Card/Card.vue';
import { Head } from '@inertiajs/inertia-vue3';
import Table from "../../Components/Table/Table.vue";
import Td from "../../Components/Table/Td.vue";
import Actions from "../../Components/Table/Actions.vue";

defineProps({
    roles: {
        type: Object,
        default: () => ({}),
    },
    headers: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Roles"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Roles
            </h2>
        </template>

        <Container>
            <Card>
                <Table :headers="headers" :items="roles">
                    <template v-slot="{ item }">
                        <Td>{{ item.name }}</Td>
                        <Td>{{ item.created_at_formatted }}</Td>
                        <Td>
                            <Actions :show-delete-link="false"/>
                        </Td>
                    </template>
                </Table>
            </Card>
        </Container>
    </BreezeAuthenticatedLayout>
</template>

<script>
export default {
    name: "Index"
}
</script>
