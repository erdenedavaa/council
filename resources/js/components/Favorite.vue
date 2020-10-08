<template>
    <button type="submit" :class="classes" @click="toggle">
        <i class="fas fa-heart"></i>
        <span v-text="count"></span>
    </button>
</template>

<script>
export default {
    name: "Favorite",
    props: ['reply'],

    data() {
        return {
            count: this.reply.favoritesCount,
            active: this.reply.isFavorited,
            userLoggedIn: this.reply.isUserLoggedIn
        }
    },

    computed: {
        classes() {
            return [
                'btn',
                this.active ? 'btn-primary' : 'btn-default'
            ];
        },

        endpoint() {
            return '/replies/' + this.reply.id + '/favorites';
        }
    },

    methods: {
        toggle() {
            if (this.userLoggedIn) {
                this.active ? this.destroy() : this.create();
            } else {
                flash('Энэ үйлдлийг хийхийн тулд заавал нэвтэрч орсон байх ёстой!');
            }

        },

        create() {
            axios.post(this.endpoint);

            this.active = true;
            this.count++;
        },

        destroy() {
            axios.delete(this.endpoint); // create the endpoint in php end.
            // blade iin daraah endpoint ruu zaah heregtei
            // "/replies/{{ $reply->id }}/favorites"

            this.active = false;
            this.count--;
        }
    }
}
</script>

<style scoped>

</style>
