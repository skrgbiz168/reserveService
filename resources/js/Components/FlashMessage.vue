<script setup>
</script>

<template>
    <div class="flex justify-center">
        <div v-if="$page.props.flash.status === 'success'"
            class="mt-4 w-3/4 text-center order-solid border-2 border-indigo-500 ">
            {{$page.props.flash.message}}
        </div>
        <div v-if="$page.props.flash.status === 'danger'"
            class="mt-4 w-3/4 text-center border-solid border-2 border-red-500">
            {{$page.props.flash.message}}
        </div>
    </div>
</template>