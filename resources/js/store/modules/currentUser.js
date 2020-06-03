const SET_CURRENT_USER = 'SET_CURRENT_USER';

module.exports = {
    
    state: () => ({
        currentUser: null
    }),

    mutations: {
        SET_CURRENT_USER (state, userData) {
            state.currentUser = userData;
        }
    },

    actions: {
        setCurrentUser ({ commit }, userData) {
            commit(SET_CURRENT_USER, userData);
        }
    }
}