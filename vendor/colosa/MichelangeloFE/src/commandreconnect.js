var PMCommandReconnect = function (rec, opts) {
    var CmdReconnect = function (port, settings) {
        // We skip one level in the class hierarchy by calling to the constructor of superclass of the current class superclass
        PMUI.command.CommandReconnect.superclass.call(this, port);

        this.before = {
            x: port.getX(),
            y: port.getY(),
            parent: port.getParent()
        };

        this.after = {
            x: settings.x,
            y: settings.y,
            parent: settings.shape
        };
    };

    CmdReconnect.prototype = new PMUI.command.CommandReconnect(rec);

    CmdReconnect.prototype.execute = function () {
        var connection = this.receiver.connection,
            canvas = this.before.parent.canvas,
            destElement,
            srcElement;

        this.receiver.setPosition(this.after.x, this.after.y)
            .dragging = false;

        PMUI.command.CommandReconnect.prototype.execute.call(this);

        if (this.after.parent !== this.before.parent) {
            canvas.regularShapes.insert(this.receiver);
        }

        srcElement = connection.getSrcPort().getParent();
        destElement = connection.getDestPort().getParent();

        srcElement.addOutgoingConnection(connection);
        destElement.addIncomingConnection(connection);
    };
    CmdReconnect.prototype.undo = function () {
        var connection,
            destPort,
            srcPort,
            otherActivity;

        PMUI.command.CommandReconnect.prototype.undo.call(this);

        connection =  this.receiver.connection;
        destPort = connection.getDestPort();
        srcPort = connection.getSrcPort();

        if (this.after.parent !== this.before.parent) {
            if (destPort === this.receiver) {
                otherActivity = srcPort.getParent();
                otherActivity.removeOutgoingConnection(connection);
                otherActivity.addOutgoingConnection(connection);
                this.after.parent.removeIncomingConnection(connection);
                this.before.parent.addIncomingConnection(connection);
            } else {
                otherActivity = destPort.getParent();
                otherActivity.removeIncomingConnection(connection);
                otherActivity.addIncomingConnection(connection);
                this.after.parent.removeOutgoingConnection(connection);
                this.before.parent.addOutgoingConnection(connection);
            }
        }
    };
    return new CmdReconnect(rec, opts);
};