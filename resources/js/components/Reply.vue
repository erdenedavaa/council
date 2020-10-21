<template>
    <div :id="'reply-'+id" class="my-2" v-bind:class="{ 'd-none': isDeleted }">
        <div class="card" >
            <div class="card-header"  :class="isBest? 'bg-success text-white' : ''">
                <div class="d-flex justify-content-between align-items-center" >
                    <h6 class="mb-0">
                        <a :href="'/profiles/' + reply.owner.name"
                            v-text="reply.owner.name">
                        </a> said <span v-text="ago"></span>
                        <!-- moment.js time deer dajgui gej bn. Front
                         endees bid Carbon helder ruu can't access -->
                    </h6>

                    <div v-if="signedIn">
                        <favorite :reply="reply"></favorite>
                    </div>

                </div>
            </div>

            <div class="card-body">
                <div v-if="editing">
                    <form @submit="update">
                        <div class="form-group">
                            <wysiwyg v-model="body"></wysiwyg>
<!--                            <textarea class="form-control" v-model="body" required></textarea>-->
                        </div>

                        <button class="btn btn-sm btn-primary">Update</button>
                        <button class="btn btn-sm btn-link" @click="editing = false" type="button">Cancel</button>
                    </form>

                </div>

                <div ref="body" v-else>
                    <highlight :content="body"></highlight>
                </div>

            </div>

            <div class="card-footer d-flex" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
                <div v-if="authorize('owns', reply)">
                    <button class="btn btn-secondary btn-sm mr-2" @click="editing = true">Edit</button>
                    <button class="btn btn-danger btn-sm mr-2" @click="destroy">Delete</button>
                </div>

                <button class="btn btn-outline-primary btn-sm ml-auto" @click="markBestReply" v-if="authorize('owns', reply.thread) && ! isBest">Best Reply?</button>
<!--                // reply.thread нь pass through the component, then access to it.-->
<!--                // $reply->thread гэсэнтэй ижил, ойролцоо -->
            </div>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';
    import Highlight from './Highlight.vue';
    import moment from 'moment';

    export default {
        name: "Reply",

        props: ['reply'],

        components: { Favorite, Highlight },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isDeleted: false,
                isBest: this.reply.isBest,
                // reply: this.data
                // prop -ийн нэрийг 'reply' гэж солисон тул дээрхийг арилгана
            };
        },

        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow() + '...';
            },

            // signedIn() {
            //     return window.App.signedIn;
            // }
            // app.js deer VUe.prototype deer zaagaad ugsun tul global bolchij bga ium shig bn.
        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
            // utga ni herev best-reply-selected iin id ni my id-tai equal bval
            // bi best reply bolno, return true, or return false
            // then update and rerender
        },



        methods: {
            update() {
                axios.patch(
                    '/replies/' + this.id,
                    {
                        body: this.body
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });


                this.editing = false;

                flash('Updated');
            },

            destroy() {
                axios.delete('/replies/' + this.id);

                // Jeffrey solution
                this.$emit('deleted', this.id);
            },

            markBestReply() {
                // this.isBest = true;
                // created() deer zaagaad ugj bga tul hereggui bolloo

                axios.post('/replies/' + this.id + '/best');

                // server deer persist hiigdehed doorhiig shuud FIRE UP hiine
                window.events.$emit('best-reply-selected', this.id);
            }
        }
    }
</script>

<!--<style scoped>-->
<!--    .fade-enter-active,-->
<!--    .fade-leave-active {-->
<!--        transition: opacity 1s-->
<!--    }-->

<!--    .fade-enter,-->
<!--    .fade-leave-to-->
<!--        /* .fade-leave-active in <2.1.8 */-->

<!--    {-->
<!--        opacity: 0-->
<!--    }-->
<!--</style>-->
