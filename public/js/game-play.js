"use strict";


//IIFE équivalent du invoke en PHP !!!
(function($window, $document, $bootstrap) {
    const urlParams = new URLSearchParams($window.location.search);     //et voilà comment on fait un GET en JS
    let hasReleaseState = false;

    class Player {
        //Initialisation des données du player pour le WS
        step = 0;
        isConnected = false;
        id = 0;
        pawn = "";
        isMyTurn = false;
        name= "";
        //on va chercher l'image
        get pawnSource() {
            return "/images/tortle"+this.pawn+".png"
        }
        
        constructor(object) {
            object = object || {};                                      //encore plus court que le ternaire
            Object.assign(this, object);                                //on copie les valeurs de l'objet sur les valeurs initiales
        }
    }
    
    class Table {
        id;
        players = [];
        isManager = false;
        isContinue = true;
        tableManagerId= 0;
        currentTurn;
        canStart = false;

        constructor(object) {
            object = object || {};
            const tableManagerId = object.tableManagerId || 0;
            object.isManager = tableManagerId > 0;
            Object.assign(this, object);
        }
    }
    
    class Answer {
        id;
        label;
    
        constructor(object) {
            object = object || {};
            Object.assign(this, object);
        }
    }
    
    class QuestionSet {
        id;
        label;
        answers = []; // {id, label}
        needVerification = false;
        selectedAnswer;
        isCorrectAnswer;
        constructor(object) {
            object = object || {};
            Object.assign(this, object);
        }
    }
    
    class Message
    {
        static get createTable() {
            return "create";
        }
    
        static get joinPlayer() {
            return "joinPlayer";
        }
    
        static get getQuestion() {
            return 'getQuestion';
        }
    
        static get setAnswer() {
            return 'setAnswer';
        }
    
        static get sendVerification() {
            return "sendVerification";
        }
    
        static _buildParams(action, params) {
            return JSON.stringify({
                action: action,
                params: params,
            });
        }
    
        static _sendMessage(connection, action, params) {
            const message = Message._buildParams(action, params);
            console.log("Message sending. Message: ", message);
            connection.send(message);
        }
    
        static sendCreateTableMessage(connection, table)
        {
            const params = {
                tableId: table.id,
                userId: table.tableManagerId,
                players: [],
            }
    
            if (table.players) {
                table.players.forEach(player => {
                    params.players.push({
                        id: player.id,
                        pawn: player.pawn,
                    });
                })
            }
    
            Message._sendMessage(connection, Message.createTable, params);
        }
    
        static sendTableJoinMessage(connection, playerInfo)
        {
            Message._sendMessage(connection, Message.joinPlayer, {
                userId: playerInfo.id,
                tableId: playerInfo.tableId,
            });
        }
    
        static sendDifficultySelected(connection, player, difficulty) {
            Message._sendMessage(connection, Message.getQuestion, {
                userId: player.id,
                tableId: player.tableId,
                difficulty: difficulty,
            })
        }
    
        static sendAnswer(connection, player, answer) {
            Message._sendMessage(connection, Message.setAnswer, {
                userId: player.id,
                tableId: player.tableId,
                answer: answer,
            });
        }
    
        static sendAnswerVerification(connection, manager, isCorrectAnswer) {
            Message._sendMessage(connection, Message.sendVerification, {
                userId: manager.id,
                tableId: manager.tableId,
                isCorrectAnswer: isCorrectAnswer,
            })
        }
    }



    Vue.component('component-player-status', {
        template: "#component-player-status",
        props: {
            player : Object,
        },
        data: function(){
            return {
                MAX_STEPS: 48,
            }
        },
        computed: {
        },
        delimiters: ['${', '}'],
    });

    
    Vue.component('component-difficulty-selector', {
        template: "#component-difficulty-selector",
        props: [],
        data: function(){
            return {
                MAX_DIFFICULTY: 6,
                model: null,
            }
        },
        created: function() {
            
        },
        mounted: function() {
            this.model = new $bootstrap.Modal($document.getElementById("difficulty-selector-model"));
            this.model.show();
        },
        computed: {
            selectedDifficulty: function(){
                return this.$root.selectedDifficulty;
            },
        },
        methods: {
            eventSelectDifficulty: function(difficulty) {
                this.$root.selectDifficulty(difficulty);
            },
        },
        destroyed: function() {
            this.model.hide();
        },
        delimiters: ['${', '}'],
    });


    Vue.component('component-winner-result', {
        template: "#component-winner-result",
        props: {
            players: Array,
            currentPlayer: Object,
        },
        data: function(){
            return {
                model: null,
            }
        },
        created: function() {
            
        },
        methods: {
            closeWindow: function(event){
                $window.close();
            },
        },
        mounted: function() {
            this.model = new $bootstrap.Modal($document.getElementById("winner-result-model"));
            this.model.show();
        },
        destroyed: function() {
            this.model.hide();
        },
        delimiters: ['${', '}'],
    });
    
    const vm = new Vue({
        el: '#game-container',
        data: {
            pc: null,
            table: new Table(),
            questionSet: new QuestionSet(),
            selectedDifficulty: 0,
            playerStep: 0,
            isMyTurn: false,
            currentPlayer: {
                id: 0,
                tableId: 0,
            },
            gameResult: [],
        },
        created: function () {
            const that = this;
            this.pc = new WebSocket(brokerHost);
            this.pc.onopen = function(){
                console.log("Connection Started");
                that.initApplication();
            }
            this.pc.onmessage = function(e) {
                const message = JSON.parse(e.data);
                console.log("Message received. Message: ", message);
                if (!message.hasOwnProperty('action')) {
                    return;
                }
    
                that.handleRequest(message.action, message.params || {});
            }

            this.pc.onclose = function(e) {
                console.log("Connection Ended.");
                hasReleaseState = true;
            }

            this.pc.onerror = function(e) {
                console.log("Connection Error", e);
                that.pc.close();
            };


        },
        computed: {
        },
        methods: {
            handleRequest: function(action, params) {
                if (!this.canReceiveMessage()) {
                    return;
                }

                switch(action) {
                    case "player_connected":
                        this.handlePlayerConnected(params);
                        break;
                    case "get_spell":
                        this.handleGetPlayerSpell(params);
                        break;
                    case "set_spell":
                        this.handleSetPlayerSpell(params);
                        break;
                    case "set_question":
                        this.handleSetSpellQuestion(params);
                        break;
                    case "answer_reply":
                        this.handleAnswerReply(params);
                        break;
                    case "ask_answer_verification":
                        this.handleAskAnswerVerification(params);
                        break;
                    case "update_status":
                        this.handleUpdatePlayers(params);
                        break;
                    case "player_result":
                        this.handleDisplayGameResult(params);
                        break;
                    case "error_can_not_join":
                        this.handleJoinError(params);
                        break;
                    default:
                        throw new Error("Invalid message received");
                }
            },
            handlePlayerConnected: function(data) {
                this._updateTable(data);
            },
            handleGetPlayerSpell: function(data) {
                this._setPlayerTurn(data);
                this.isMyTurn = true;
                this.playerStep = 1;
                this.selectedDifficulty = 0;
            },
            handleSetPlayerSpell: function(data) {
                this._setPlayerTurn(data);
                this.isMyTurn = false;
            },
            handleSetSpellQuestion: function(data) {
                const questionSetObj = data.questionSet || {}; 
                const answers = questionSetObj.answers || [];
                questionSetObj.answers = [];
                answers.forEach(answer => {
                    questionSetObj.answers.push(new Answer(answer));
                });
                // questionSetObj.needVerification = false;
                this.selectedDifficulty = questionSetObj.difficulty;
    
                this.questionSet = new QuestionSet(questionSetObj);
                this.playerStep = 2;
            },
            handleAnswerReply: function(data) {
                const playerId  = data.playerId || 0;
                this.questionSet.selectedAnswer  = data.answer || 0;
                this.questionSet.isCorrectAnswer = data.isCorrectAnswer || false;
                this.playerStep = 4;
            },
            handleAskAnswerVerification: function(data) {
                this.questionSet.needVerification = true;
            },
            handleUpdatePlayers: function(data) {
                this._updateTable(data);
            },
 
            selectAnswer: function(answer) {
                if (this.playerStep != 2 || this.table.isManager || !this.isMyTurn || this.questionSet.needVerification) {
                    return false;
                }
    
                this.questionSet.selectedAnswer = answer;
                this.playerStep = 3;
                Message.sendAnswer(this.pc, this.currentPlayer, answer);
    
                return true;
            },
            setAnswerVerification: function(verification) {
                if (!this.table.isManager || this.playerStep != 2) {
                    return false;
                }
    
                this.playerStep = 3;
                const manger = {
                    id: this.table.tableManagerId,
                    tableId: this.table.id,
                }
                Message.sendAnswerVerification(this.pc, manger, verification);
            },
            selectDifficulty: function(difficulty) {
                this.selectedDifficulty = difficulty;
                Message.sendDifficultySelected(this.pc, this.currentPlayer, difficulty);
            },
            handleJoinError: function(data) {
                let text = "";
                if (data.reason) {
                    switch (data.reason) {
                        case 'invalid_table':
                            text = "Invalid game.";
                            break;
                        case 'table_already_used':
                        case 'game_over':
                            text = "expired link";
                            break;
                        case 'already_started':
                            text = "Game already started.";
                            break;
                        case 'invalid_player':
                            text = "Invalid link";
                            break;
                        default:
                            break;
                    }
                }

                this.closeConnection();
                this.table.isContinue = false;

            },

            _updateTable: function(data) {
                if(data.table) {
                    const playersObj = data.table.players || [];
                    const players = [];
                    if (playersObj && Array.isArray(playersObj)) {
                        playersObj.forEach(player => {
                            players.push(new Player(player));
                        });
                        data.table.players = players;
                    }
                    if (this.table && this.table.tableManagerId) {
                        data.table.tableManagerId = this.table.tableManagerId;
                    }
                    this.table = new Table(data.table);
                }
            },
            _setPlayerTurn: function(data) {
                if (data && data.currentTurn !== undefined) {
                    this.table.currentTurn = data.currentTurn;
                    this.table.players.forEach((player, index) => {
                        if (this.table.currentTurn == index) {
                            player.isMyTurn = true;
                        } else {
                            player.isMyTurn = false;
                        }
                    });
                }
            },
            canReceiveMessage: function()
            {
                return this.table.isContinue;
            },
            closeConnection: function()
            {
                this.pc.close();
            },
            replyAnswerClass: function(answer){
                if (!answer || !answer.id) {
                    return "";
                }
                if (!this.questionSet.selectedAnswer) {
                    return "";
                }
    
                if (this.questionSet.selectedAnswer != answer.id) {
                    return "";
                }
    
                if (this.playerStep == 3) {
                    return "text-primary";
                }
    
                return this.questionSet.isCorrectAnswer ? "text-success" : "text-danger";
            },
            initApplication: function() {
                const gameStarter = JSON.parse(atob(gameToken));
                console.log(gameStarter);
                if (!gameStarter 
                        || !gameStarter.event 
                        || !["create", "join"].includes(gameStarter.event)
                        || !gameStarter.userId
                        || !gameStarter.tableId
                        || (gameStarter.event == "create" && (!gameStarter.players || !Array.isArray(gameStarter.players) || !gameStarter.players.length))
                    ) {
                    this.closeConnection();
                    this.table.isContinue = false;
                    
                    return false;
                }

                switch (gameStarter.event) {
                    case "create":
                        const players = [];
                        gameStarter.players.forEach(player => {
                            players.push(new Player({
                                id: player.id, 
                                pawn: player.pawn,
                            }));
                        });
                        this.table = new Table({
                            id: gameStarter.tableId, 
                            players: players, 
                            tableManagerId: gameStarter.userId,
                        });
                        Message.sendCreateTableMessage(this.pc, this.table);
                        break;
                    case "join":
                        this.currentPlayer.id = gameStarter.userId;
                        this.currentPlayer.tableId = gameStarter.tableId;
                        Message.sendTableJoinMessage(this.pc, this.currentPlayer);
                        break;
                    default:
                        break;
                }
            }
        },
        delimiters: ['${', '}'],
    });
})(window, document, bootstrap);
