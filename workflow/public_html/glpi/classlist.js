(function () {
   // this declaration is needed when using IE9 and earlier
   // as this property is not existing for those.
   if (!document.documentElement.classList) { // html tag has no 'classList' property
      Object.defineProperty(HTMLElement.prototype, 'classList', {
         get: function () {
               return new function(node) {
                  var self = node.className.trim().split(/\s+/);

                  self.item = function (index) {
                     return self[index];
                  };

                  self.contains = function (className) {
                     return self.indexOf(className) !== -1;
                  };

                  self.add = function (className) {
                     if (!self.contains(className)) {
                           self.push(className);
                           node.className = self.toString();
                     }
                  };

                  self.remove = function (className) {						
                     var index = self.indexOf(className);
                     if (index >= 0) {
                           self.splice(index, 1);
                           node.className = self.toString();
                     }
                  };

                  self.toggle = function (className) {
                     if (self.contains(className)) {
                           self.remove(className);
                     } else {
                           self.add(className);
                     }
                  };
                  
                  self.toString = function() {
                     return self.join(' ');
                  };

                  return self;
               }(this);
         },
         enumerable: true
      });
   }
}());



