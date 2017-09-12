/**
 * Created by juanfelipe on 12/09/17.
 */

define(['jquery', 'core/modal_factory', 'core/templates', 'core/str', 'core/notification', 'core/ajax'],
    function($, ModalFactory, Templates, str, Notification, ajax) {
        return {
            init: function (cmid, userid) {
                $("input[name=agreement]").click(function() {
                    if ($("input[name=agreement]").is(':checked')) {
                        var promise = ajax.call(
                            [{
                                methodname: 'plagiarism_safeassign',
                                args: {
                                    cmid: cmid,
                                    userid: userid,
                                    flag: 1
                                }
                            }]
                        )[0];
                        promise.done(function(response) {
                            console.log(response);
                        }).fail(function(ex) {
                            console.log(response);
                        });
                    }else{
                        var promise = ajax.call(
                            [{
                                methodname: 'plagiarism_safeassign',
                                args: {
                                    cmid: cmid,
                                    userid: userid,
                                    flag: 0
                                }
                            }]
                        )[0];
                        promise.done(function(response) {
                            console.log(response);
                        }).fail(function(ex) {
                            console.log(response);
                        });
                    }
                });
            }
        }
    }
);
