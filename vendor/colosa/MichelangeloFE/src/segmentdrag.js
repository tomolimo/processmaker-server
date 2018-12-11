var PMSegmentDragBehavior = function (options) {

};
PMSegmentDragBehavior.prototype = new PMUI.behavior.ConnectionDragBehavior();

/**
 * On drag handler, creates a connection segment from the shape to the current
 * mouse position
 * @param {PMUI.draw.CustomShape} customShape
 * @return {Function}
 */
PMSegmentDragBehavior.prototype.onDrag = function (customShape) {
    return function (e, ui) {
        var canvas = customShape.getCanvas(),
            endPoint = new PMUI.util.Point(),
            realPoint = canvas.relativePoint(e);
        if (canvas.connectionSegment) {
            //remove the connection segment in order to create another one
            $(canvas.connectionSegment.getHTML()).remove();
        }
        //Determine the point where the mouse currently is
        endPoint.x = realPoint.x * customShape.canvas.zoomFactor;
        endPoint.y = realPoint.y * customShape.canvas.zoomFactor;

        //creates a new segment from where the helper was created to the
        // currently mouse location
        canvas.connectionSegment = new PMUI.draw.Segment({
            startPoint: customShape.startConnectionPoint,
            endPoint: endPoint,
            parent: canvas,
            color: new PMUI.util.Color(92, 156, 204),
            zOrder: PMUI.util.Style.MAX_ZINDEX * 2
        });
        //We make the connection segment point to helper in order to get
        // information when the drop occurs
        canvas.connectionSegment.pointsTo = customShape;
        //create HTML and paint
        //canvas.connectionSegment.createHTML();
        canvas.connectionSegment.paint();
    };
};