<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { onMounted, watch } from "vue";

import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import Container from '@/Components/Container.vue';
import Card from '@/Components/Card/Card.vue';
import Button from "@/Components/Button.vue";
import InputGroup from "@/Components/InputGroup.vue";
import SelectGroup from "@/Components/SelectGroup.vue";
import CheckboxGroup from "@/Components/CheckboxGroup.vue";
import { kebabCase, replace } from "lodash";

const form = useForm({
    name: '',
    slug: '',
    active: true,
    parent_id: '',
});

watch(() => form.name, name => {
    if (!props.edit) {
        form.slug = kebabCase(replace(name, /&./, "and"))
    }
})

const submit = () => {
    props.edit
        ? form.put(route(`admin.${props.routeResourceName}.update`, {id: props.item.id, name: form.name}))
        : form.post(route(`admin.${props.routeResourceName}.store`), {
            onSuccess: () => {
                form.reset('name')
                form.reset('slug')
                form.reset('active')
                form.reset('parent_id')
            },
        });
}

onMounted(() => {
    if (props.edit) {
        form.name = props.item.name;
        form.slug = props.item.slug;
        form.active = props.item.active;
        form.parent_id = props.item.parent_id;
    }
});

const props = defineProps({
    title: {
        type: String,
        default: 'Add Category'
    },
    edit: {
        type: Boolean,
        default: false,
    },
    item: {
        type: Object,
        default: () => ({}),
    },
    routeResourceName: {
        type: String,
        required: true,
    },
    rootCategories: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <Head :title="title"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ title }}
            </h2>
        </template>

        <Container>
            <Card>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-6">
                        <InputGroup v-model="form.name" :error-message="form.errors.name" label="Name" required/>
                        <InputGroup v-model="form.slug" :error-message="form.errors.slug" label="Slug" required/>

                        <SelectGroup v-model="form.parent_id" :error-message="form.errors.parent_id"
                                     :items="rootCategories"
                                     label="Parent Category"/>

                        <div class="mt-6">
                            <CheckboxGroup v-model:checked="form.active" :error-message="form.errors.active"
                                           label="Active"/>
                        </div>
                    </div>

                    <div class="mt-4">
                        <Button :disabled="form.processing">
                            {{ form.processing ? 'Saving...' : 'Save' }}
                        </Button>
                    </div>
                </form>
            </Card>
        </Container>
    </BreezeAuthenticatedLayout>
</template>

<script>
export default {
    name: "Create"
}
</script>
