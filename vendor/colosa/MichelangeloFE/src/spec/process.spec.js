describe('PMProcess', function() {
    var process, 
        projectId;

    beforeEach(function () {
        
        projectId = PMUI.generateUniqueId();
        process = new PMProcess();
    });
    it("Should be a object", function() {
      expect(typeof process).toEqual("object");
    });

    it("Should be a random string", function() {
        process = new PMProcess({
            id: projectId,
            name: 'Testing Default Process'
        });
        expect(typeof projectId === "string").toEqual(true);
    });
    it("Should add new elements to canvas", function() {
        var r,
        classes = [
            {
                class: PMActivity,
                types: [
                    "TASK",
                    "SUB_PROCESS"
                ]
            },
            {
                class: PMEvent,
                types: [
                    "START",
                    "START_MESSAGE",
                    "START_TIMER",
                    "END"
                ]
            },
            {
                class: PMGateway,
                types: [
                    "COMPLEX",
                    "EXCLUSIVE",
                    "PARALLEL",
                    "INCLUSIVE"
                ]
            },
            {
                class: PMArtifact,
                types: [
                    "ANNOTATION"
                ]
            },
            {
                class: PMLine,
                types: [
                    "VERTICAL_LINE",
                    "HORIZONTAL_LINE"
                ]
            }
        ];
        while (r < classes.length || r.classes.length) {
            r = Math.floor(Math.random(0,9) * 10);
        };

        obj = new classes[r].class();
        //console.log(obj);
        // var new elements = {
        //         id: pasteElement.id,
        //         relatedElements: [],
        //         relatedObject: pasteElement,
        //         type: pasteElement.type || pasteElement.extendedType
        //     };
        // process.addElement();
    });

});