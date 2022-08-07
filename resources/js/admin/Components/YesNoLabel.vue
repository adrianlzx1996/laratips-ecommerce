<script setup>
defineProps({
    active: Boolean
})
</script>
<template>
    <Button :color="active ? 'green' : 'red'" small>
        {{ active ? 'Yes' : 'No' }}
    </Button>
</template>

<script>
import Button from "./Button.vue";

export default {
    name: "YesNoLabel",
    components: {Button}
}
</script>

<style scoped>

</style>
