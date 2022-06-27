<script wb-app>
    if (modSynapse == undefined) {
        var modSynapse = function() {
            let port = '{{port}}';
            let host = '{{host}}';
            let hash = '{{roomhash}}';
            let user = '{{userhash}}';
            let json = {};
            const conn = new WebSocket('ws://' + host + ':' + port);

            if (document.modSynapse !== undefined) {
                wbapp.trigger('modSynapse', document.modSynapse);
                console.log(document.modSynapse);
                return document.modSynapse;
            }

            var synapse_connect = function() {
                if (document.modSynapse && document.modSynapse.readyState !== undefined) return document.modSynapse;
                try {
                    console.log(conn);
                    conn.room = hash;
                    conn.user = user;


                    if (!conn.readyState) {
                        wbapp.toast("Ошибка","Нет соединения с сервером. Подождите 2-3 минуты...",{delay:2000,bgcolor:'warning'});
                        setTimeout(function(){
                            synapse_connect()
                        },3000)
                        return
                    }

                    conn.put = function(data) {
                        data.room = hash;
                        data.user = user;
                        data._token = wbapp._session.token;
                        data.cast == undefined ? data.cast = 'room' : null;
                        conn.send(json_encode(data))
                    }
                    conn.onopen = function(e) {
                        conn.put({
                            'type': 'sysmsg',
                            'action': 'join'
                        })
                        conn.start == undefined ? null : conn.start(e);
                        console.log("Connection established!")
                    };
                    conn.onmessage = function(e) {
                        let data = json_decode(e.data)
                        if (conn.get !== undefined) {
                            conn.get(data);
                        } else {
                            console.log(data);
                        }
                    };

                    conn.onclose = function(e) {
                        delete document.modSynapse;
                        console.log("Connection closed!");
                        let timer = setInterval(function() {
                            synapse_connect();
                            if (document.modSynapse !== undefined && document.modSynapse.readyState == 1) {
                                clearInterval(timer);
                            }
                        }, 3000)
                    }
                    window.addEventListener('beforeunload', function(event) {
                        conn.close()
                    })

                    document.modSynapse = conn;
                    wbapp.trigger('modSynapse', document.modSynapse);
                    // эвент сохранения записи
                    wbapp.on('wb-save-done', function(e, data) {
                        conn.put({
                            'type': 'sysmsg',
                            'action': 'formsave',
                            'even': e,
                            'params': data.params,
                            'cast': 'wide'
                        });
                    })
                    return document.modSynapse;
                } catch (error) {
                    setTimeout(function() {
                        return synapse_connect();
                    }, 3000)
                }
            }
            return synapse_connect();
        }
        modSynapse();
    }
</script>