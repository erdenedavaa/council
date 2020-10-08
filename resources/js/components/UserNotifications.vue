<template>
    <div class="navbar-nav">
        <li class="nav-item dropdown" v-if="notifications.length">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-bell"></i>
            </a>

            <ul class="dropdown-menu">
                <li v-for="notification in notifications">
                    <a :href="notification.data.link"
                       v-text="notification.data.message"
                       @click="markAsRead(notification)"></a>
                </li>
            </ul>

<!--            <div class="dropdown-menu" aria-labelledby="navbarDropdown">-->
<!--                <a class="dropdown-item" href="#">Action</a>-->
<!--                <a class="dropdown-item" href="#">Another action</a>-->
<!--                <div class="dropdown-divider"></div>-->
<!--                <a class="dropdown-item" href="#">Something else here</a>-->
<!--            </div>-->
        </li>
    </div>
</template>

<script>
export default {
    name: "UserNotifications",

    data() {
        return {
            notifications: false
        }
    },

    created() {
        axios.get("/profiles/" + window.App.user.name + "/notifications")
            .then(response => this.notifications = response.data);
    },

    methods: {
        markAsRead(notification) {
            axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
        }
    },
}
</script>

<style scoped>

</style>
