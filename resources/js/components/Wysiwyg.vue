<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">

        <trix-editor ref="trix" input="trix" class="trix-content" :placeholder="placeholder"></trix-editor>
    </div>
</template>

<script>
import Trix from 'trix';

export default {
    name: "Wysiwyg",

    props: ['name', 'value', 'placeholder', 'shouldClear'],

    mounted() {
        this.$refs.trix.addEventListener('trix-change', e => {
            this.$emit('input', e.target.innerHTML);
        });

        this.$watch('shouldClear', () => {
            // console.log('It changed');
            this.$refs.trix.value = '';
        });
    }
}
</script>

<style scoped>

</style>
