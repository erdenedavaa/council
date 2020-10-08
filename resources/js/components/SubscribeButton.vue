<template>
    <button :class="classes" @click="subscribe" v-text="btnText"></button>
</template>

<script>
export default {
    name: "SubscribeButton",
    props: ['active'],

    data() {
        return {
            isActive: this.active
        }
    },

    // deerh prop-oor active esehee shiidej bga tul hereggui bolson
    // data() {
    //     return {
    //         active: false
    //     }
    // },

    computed: {
        classes() {
            return ['btn', this.isActive ? 'btn-primary' : 'btn-secondary'];
        },
        btnText() {
            return this.isActive ? 'Unsubscribe' : 'Subscribe'
        }
    },

    methods: {
        subscribe() {
            axios[
                (this.isActive ? 'delete' : 'post')
            ](location.pathname + '/subscriptions');

            // this.active = ! this.active;
            this.isActive = ! this.isActive;
        }
    }
}
</script>

<style scoped>

</style>
