<script>
import Replies from '../components/Replies';
import SubscribeButton from '../components/SubscribeButton.vue';

export default {
    // name: "Thread",
    props: ['thread'],

    components: {Replies, SubscribeButton},
    // ene bol child component

    data() {
        return {
            repliesCount: this.thread.replies_count,
            // this is coming from eloquent query results
            locked: this.thread.locked,
            editing: false,
            title: this.thread.title,
            body: this.thread.body, // $thread->body (in php)
            form: {}
        }
    },

    created() {
        this.resetForm();
    },

    methods: {
        toggleLock() {

           // axios.post('/locked-threads/' + this.thread.slug);
            // toggle hiij bga tul deerhiig daraah baidlaar hiine
            let uri = `/locked-threads/${this.thread.slug}`;

            axios[this.locked ? 'delete' : 'post'](uri);

            this.locked = ! this.locked;
        },

        update() {
            let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;

            axios.patch(uri, this.form).then(() => {
                this.editing = false;
                this.title = this.form.title;
                this.body = this.form.body;

                flash('Your thread has been updated.')
            });
        },

        resetForm() {
            this.form = {
                title: this.thread.title,
                body: this.thread.body
            };

            this.editing = false;
        }
    }
}
</script>

