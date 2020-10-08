let user = window.App.user;

// let authorizations = {
//     updateReply(reply) {
//         return reply.user_id === user.id;
//     }
// };
//
// module.exports = authorizations;

module.exports = {
    // updateReply(reply) {
    //     return reply.user_id === user.id;
    // },
    //
    // updateThread (thread) {
    //     return thread.user_id === user.id;
    // },
    // thread table-ийн user_id нь authenticated user.id-тай ижил гэсэн үг

    owns (model, prop = 'user_id') {
        return model[prop] === user.id;
    },

    isAdmin() {
        return ['Ongoo', 'JohnDoe', 'JaneDoe'].includes(user.name);
        // Array нь auth user name агуулсан байна уу шалгаж байна,
        // хэрвээ агуулж байвал there a admin (if so)
    }
};
