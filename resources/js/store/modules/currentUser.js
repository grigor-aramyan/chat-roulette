
const SET_CURRENT_USER = 'SET_CURRENT_USER';
const SET_CURRENT_USER_MODE = 'SET_CURRENT_USER_MODE';
const ADD_FETCHED_CONNECTIONS = 'ADD_FETCHED_CONNECTIONS';
const ADD_CONNECTION = 'ADD_CONNECTION';

module.exports = {
    
    state: () => ({
        currentUser: null,
        connections: null
    }),

    mutations: {
        SET_CURRENT_USER (state, userData) {
            state.currentUser = userData;
        },
        SET_CURRENT_USER_MODE (state, mode) {
            if (state.currentUser) {
                state.currentUser.mode = mode;
            }
        },
        ADD_FETCHED_CONNECTIONS (state, connections) {
            state.connections = connections;
        },
        ADD_CONNECTION (state, connection) {
            if (state.connections == null) {
                state.connections = [];
            }

            state.connections.push(connection);
        }
    },

    actions: {
        setCurrentUser ({ commit }, userData) {
            commit(SET_CURRENT_USER, userData);
        },
        setCurrentUserMode({ commit }, mode) {
            commit(SET_CURRENT_USER_MODE, mode);
        },
        addFetchedConnections({ commit }, connections) {
            commit(ADD_FETCHED_CONNECTIONS, connections);
        },
        addConnection({ commit }, connection) {
            commit(ADD_CONNECTION, connection);
        }
    }
}