<template>
    <div>
        <div v-if="! signedIn">
            <p class="text-center">
                Please <a href="/login">sign in</a> to participate in this discussion.
            </p>
        </div>

        <div v-else-if="! confirmed">
            To participate in this thread, please check your email and confirm your account.
        </div>

        <div v-else>
            <div class="form-group">
                <wysiwyg name="body" v-model="body" placeholder="Have something to say?" :shouldClear="completed"></wysiwyg>
<!--                    <textarea name="body"-->
<!--                              id="body"-->
<!--                              rows="5"-->
<!--                              class="form-control"-->
<!--                              required-->
<!--                              placeholder="Have something to say?"-->
<!--                              v-model="body"></textarea>-->
            </div>

            <button type="submit"
                    class="btn btn-primary"
                    @click="addReply">Post</button>
        </div>


<!--        <p class="text-center" v-else>-->
<!--            Please <a href="/login">sign in</a> to participate in this discussion.-->
<!--        </p>-->
    </div>
</template>

<script>
import Tribute from "tributejs";

export default {
    name: "NewReply",

    data() {
        return {
            body: '',
            completed: false
        };
    },
    //
    // computed: {
    //     signedIn() {
    //         return window.App.signedIn;
    //     }
    // },
    // app.js deer vue.prototype.signedIn geed zaagaad ugchihsun tul hereggui.

    computed: {
        confirmed() {
            return window.App.user.confirmed;
        }
    },

    mounted() {
        let tribute = new Tribute({
            // column to search against in the object (accepts function or string)
            lookup: 'value',
            // column that contains the content to insert by default
            fillAttr: 'value',
            values: function(query, cb) {
                axios.get('/api/users', {params: {name: query}} )
                    .then(function(response){
                        console.log(response);
                        cb(response.data);
                    });
            },
        });
        tribute.attach(document.querySelectorAll("#body"));
    },

    methods: {
        addReply() {
            axios.post(location.pathname + '/replies', { body: this.body })
                .catch(error => {
                    flash(error.response.data, 'danger');
                })
                .then( ({data}) => {
                    this.body = '';
                    this.completed = true; // NewReply has been done, completed gesen utgatai

                    flash('Your reply has been posted.');

                    // this trix iin child elemtn ruu shiljij bga tul davharddlaa
                    // this.$refs.trix.$refs.trix.value = '';

                    this.$emit('created', data);
                });
        }
    }
}
</script>

<style scoped>

</style>
