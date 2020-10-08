<template>
    <div>
        <div class="d-flex align-items-center mb-2">
            <img :src="avatar" alt="" width="50" height="50" class="mr-1">

            <h1 v-text="user.name" class="m-0"></h1>
        </div>

        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
        </form>


    </div>
</template>

<script>
import ImageUpload from './ImageUpload.vue';

export default {
    name: "AvatarForm",
    props: ['user'],

    components: { ImageUpload },

    data() {
        return {
            avatar:  this.user.avatar_path
        }
    },

    computed: {
        canUpdate() {
            return this.authorize(user => user.id === this.user.id)
        }
    },

    methods: {
        onLoad(avatar) {
            // avatar ni $emit-ees irj bga payload ium

            this.avatar = avatar.src;
            // Persist to the server
            this.persist(avatar.file);
        },

        persist(avatar) {
            let data = new FormData();

            data.append('avatar', avatar);
            // ene deerh 2 doh avatar ni file object (jinhene picture, not dataurl)

            axios.post(`/api/users/${this.user.name}/avatar`, data)
                .then(() => flash('Avatar uploaded!'));
        }
    },
}
</script>

<style scoped>

</style>
