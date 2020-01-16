<template>
    <div>
        <div v-if="$userCan(permission)">
            <slot></slot>
        </div>
        <div v-else>
            <fndry-widget v-if="!noWidget" no-header no-footer>
                <permission-denied></permission-denied>
            </fndry-widget>
            <div v-else>
                <permission-denied></permission-denied>
            </div>
        </div>
    </div>
</template>

<script>
    import PermissionDenied from "./PermissionDenied";

    export default {
        name: "WithPermission",
        components: {
            PermissionDenied
        },
        props: {
            noWidget: Boolean,
            permission: {
                type: String,
                required: true
            }
        }
    }
</script>
