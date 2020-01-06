<template>
    <b-badge :variant="variant" :size="size">{{item.label}}</b-badge>
</template>

<script>
    export default {
        name: "PickListBadge",
        props: {
            item: {
                type: Object,
                required: true
            },
            variant: String,
            size: String
        }
    }
</script>

