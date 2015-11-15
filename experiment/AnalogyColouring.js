/**
 * Coloring by Bas Cornelissen
 * 
 * @version 1.1
 */

var Coloring = function(container, data, height, instruction, swatchesContainer, numSwatches, entropies) {

    if (!(this instanceof Coloring)) {
        return new Coloring(container, data, height, instruction, swatchesContainer, numSwatches, entropies)
    } else {
        this.instruction = (instruction || false)
        this.objectInfo = data['objects'];
        if(typeof(container) == 'string') {
            this.container = document.getElementById(container);
        } else {
            this.container = container;
        }
        this.height = (height || 300);
        this.width = (this.height * data['ratio'] || 300);

        this.picture = document.createElement('img');
        this.picture.setAttribute('src', data['src']);
        this.picture.width = this.width;
        this.container.appendChild(this.picture)
        this.container.setAttribute('style', 
                (this.container.getAttribute('style') || '')
                + 'width:' + this.width + 'px; '
                + 'height:' + this.height + 'px; ')

        // Colors
        this.swatches = [
            // '#7fdbff', // Aqua
            '#0074d9', // blue
            // '#01ff70', // lime
            '#39cccc', // teal
            '#3d9970', // olive
            // '#2ecc40', // green
            '#ffdc00', // yellow
            '#ff851b', // orange
            '#ff4136', // red
            // '#b10dc9', // purple
            '#f012be', // fuchsia
            '#85144b', // maroon
            '#001f3f', // navy
            // '#b10dc9', // purple
            // '#aaaaaa', // gray
            // '#ffffff', // white
            // '#111111', // black
            // '#dddddd' // silver
        ];
        this.numSwatches = (numSwatches || this.objectInfo.length)
        
        this.activeColor = this.swatches[0]
        this.defaultColor = '#FFF6EE'

        // Make paper
        this.paper = new Raphael(
            this.container,
            '100%', '100%', 0, 0);
        this.paper.setViewBox(0,0,100,100);
        this.paper.canvas.setAttribute('preserveAspectRatio', 'none')


        // Draw objects
        this.objects = []
        for (i = 0; i < data['objects'].length; i++) {
            this.drawObject(data['objects'][i]['path'])
        }
        
        // Manually drawn objects
        this.drawnObjects = []

        // instruction?
        if (this.instruction == true) {
            this.paintAllObjects();

        } else if (this.instruction == 'results') {
            
            getColor = function(x){
                var red   = (1 - .7 * Math.pow(x-.2,2)) * 255
                var green = (1 - x) * 255
                var blue  = (x*x) * 80// Math.pow(x,2)) * 100
                // console.log(red,green,blue, x)
                return rgbToHex(red, green, blue);
            }

            for (var i = 0; i < this.objects.length; i++) {
                var color = getColor(entropies[i] / Math.max(...entropies))
                // console.log(i, color.replace('-',''))
                this.paintObject(i, color)
            }

            cont = document.getElementById('color');
            cont.setAttribute('style', 'position:absolute;bottom:50px; padding-left:10px;')//width:206px; height:10px; overflow:hidden;')
            for(var j=0; j<100; j++){
                var div = document.createElement('div')
                cont.appendChild(div)
                div.setAttribute('style', 'width:2px;height:10px;float:left; background-color:' + getColor(j/100));
            }

        } else {
            
            // Build color handles, inserted directly after the container.
            this.handles = []
            this.colorsContainer = document.createElement('div');
            this.colorsContainer.setAttribute('class', 'colorsContainer cf');
            this.colorsContainer.style.width = this.container.offsetWidth +'px'
            
            if(swatchesContainer) {
                document.getElementById(swatchesContainer).appendChild(this.colorsContainer);
            } else {
                this.container.parentNode.insertBefore(
                        this.colorsContainer, 
                        this.container.nextSibling) // there is no .insertAfter()
            }

            var label = document.createElement('p');
            label.innerHTML = 'Select a color:';
            this.addClass(label, 'colors-label');
            this.colorsContainer.appendChild(label);
            
            for (var i = 0; i < this.numSwatches; i++) {
                this.buildColorHandle(this.swatches[i])
            }
            var activeHandle = this.swatches.indexOf(this.activeColor);
            this.addClass(this.handles[activeHandle], 'active')

        // element is clicked, if any
        this.container.onclick = function(e) {
            var pos = this.getEventPosition(e);
            for (var i = 0; i < this.objects.length; i++) {
                var obj = this.objects[i];
                if(obj.isPointInside(pos[0], pos[1])) {
                    this.paintObject(i);
                    obj.removeClass('color-preview')
                }
            }
        }.bind(this)

        this.container.onmousemove = function(e) {
            var pos = this.getEventPosition(e);
            for (i = 0; i < this.objects.length; i++) {
                var obj = this.objects[i];
                if(obj.isPointInside(pos[0], pos[1])) {
                    if(this.activeColor != obj._color) {
                        obj.addClass('color-preview')
                    }
                    obj.attr('fill',this.activeColor)

                } else {
                    obj.removeClass('color-preview')
                    obj.attr('fill', obj._color)
                }
            }
        }.bind(this)

        }
    }

}

/**
 * Build an object based on the passed settings
 * @param  {string} path Path of the object
 * @return {object}      the object generated
 */
Coloring.prototype.drawObject = function(path) {
    var obj = this.paper.path(path);
    obj.attr('stroke', 0);
    obj.addClass('analogy-object')
    this.objects.push(obj);
    this.paintObject(this.objects.length - 1, this.defaultColor)
    return obj;
}


/**
 * Paint a given object with a given colour
 * @param  {integer} objectId Id of the objet
 * @param  {colour} color    colour to use. Defaults to the 'active color'
 * @return {object}          this
 */
Coloring.prototype.paintObject = function(objectId, color) {
    color || (color = this.activeColor);
    var obj = this.objects[objectId];

    
    if(this.handles != null) {
         for(var i=0; i<this.handles.length; i++){
            var handleColor = this.handles[i].getAttribute('data-color');
            
            // Add 'used' class to new color handle
            if(handleColor == color) {
                this.addClass(this.handles[i], 'used')
            }

            // Reset old color handle
            if(handleColor == obj._color) {
                this.removeClass(this.handles[i], 'used')
            }
         }
    }

    this.unpaintObjects(color);
    obj._color = color;
    obj.attr('fill', color);
    obj.addClass('colored')

    return this;
}

/**
 * Paints all objects with succesive colours
 * @return {object} this
 */
Coloring.prototype.paintAllObjects = function() {
    for (var i = 0; i < this.objects.length; i++) {
        this.paintObject(i, this.swatches[i])
    }
    return this;
}

/**
 * Resets colours of all object that have the given colour
 * @param  {string} color Color to reset
 * @return {object}       this
 */
Coloring.prototype.unpaintObjects = function(color) {
    for (var i = 0; i < this.objects.length; i++) {
        var obj = this.objects[i]
        if (obj.attr('fill') == color) {
            obj.attr('fill', this.defaultColor);
            obj._color = this.defaultColor;
            obj.removeClass('colored')
        }
    }
    return this;
}

/**
 * Generates a color handle
 * @param  {string} color Color of the handle
 * @return {object}       This
 */
Coloring.prototype.buildColorHandle = function(color) {
   
    // Build element
    var handle = document.createElement('a');
    handle.setAttribute('class', 'color-handle');
    handle.setAttribute('style', 'background-color: ' + color)
    handle.setAttribute('data-color', color)
    this.colorsContainer.appendChild(handle);

    // Event handles
    handle.onclick = function() {
        // activate colour if it is not active
        if (handle.getAttribute('class').indexOf('active') == -1) {
            this.resetHandles();

            var c = handle.getAttribute('data-color');
            this.activeColor = c;
            this.addClass(handle, 'active');
        }
    }.bind(this);

    // Store 
    this.handles.push(handle)

    return this;
}

Coloring.prototype.start = function() {
    this.picture.style.opacity = .4;
    console.log('please start drawing')

    if(this.path != null) {
        console.log('First complete former path')
        this.stop()
    }

    this.container.onclick = function(e) {
        var pos = this.getEventPosition(e, 3);
        if(this.firstDrawingPoint == null){ 
            this.firstDrawingPoint = pos;
        }
    
        var path = this.path || this.paper.path('M'+pos[0]+' '+pos[1])
        var pathStr = path.attr('path').toString()

        pathStr += 'L' + pos[0] + ' ' + pos[1]
        this.path = this.paper.path(pathStr)
        this.path.attr('stroke-width', .5)
        this.path.attr('stroke', '#f33')
        this.path.attr('opacity', .8)
        this.path.attr('fill', '#f33')

    }.bind(this)
}

Coloring.prototype.stop = function() {
    if(!this.path) {
        console.log('No path')
        return false
    }
    
    console.log('Completing your drawing');
    var pathStr = this.path.attr('path').toString()
    pathStr = pathStr + ' Z' + this.firstDrawingPoint[0] + ' ' + this.firstDrawingPoint[1];
    this.path = this.paper.path(pathStr)
    this.path.attr('stroke-width', .5)
    this.path.attr('stroke', '#f00')
    
    var description = prompt('Add a description of this object')
    var object = {
        'path': pathStr,
        'desc': description,
        'id': this.drawnObjects.length
    }
    this.path = null;
    this.drawnObjects.push(object)
}

Coloring.prototype.results = function() {
    if(this.path != null) {
        this.stop();
    }
    
    if(!this.outputField) {
        this.outputField = document.createElement('textarea');
        this.addClass(this.outputField, 'outputField')
        document.body.appendChild(this.outputField)
    }
    this.outputField.innerHTML =JSON.stringify(this.drawnObjects); 
}

/**
 * Deactivates all handles
 * @return object this
 */
Coloring.prototype.resetHandles = function() {
    for (i = 0; i < this.handles.length; i++) {
        var handle = this.handles[i];
        this.removeClass(handle, 'active');
    }
    return this;
}

/**
 * Returns result
 * @return {array} an array with results
 */
Coloring.prototype.getDrawing = function() {
    var results = [];
    for (i = 0; i < this.objects.length; i++) {
        obj = this.objects[i];
        var result = {
            'description': this.objectInfo[i]['description'],
            'color': obj.attr('fill')
        };
        results.push(result);
    }
    return results;
}

/**
 * Add class to element (HTML element, typically)
 * @param {object} el
 * @param {string} className
 * @return {object} Element
 */
Coloring.prototype.addClass = function(el, className) {
    origClass = el.getAttribute('class') || '';
    if (origClass == '' || origClass.indexOf(className) == -1) {
        el.setAttribute("class", origClass + ' ' + className);
    }
    return el;
}

/**
 * Removes class from an (HTML) element
 * @param  {object} el        
 * @param  {string} className 
 * @return {object}           Element
 */
Coloring.prototype.removeClass = function(el, className) {
    origClass = el.getAttribute('class');
    el.setAttribute('class', origClass.replace(' ' + className, ''));
    return el;
}


Coloring.prototype.getColoring = function() {
    var info = JSON.parse(JSON.stringify(this.objectInfo));
    for(var i=0; i<info.length; i++) {
        delete info[i]['path'];
        info[i]['color'] = '';
        var objColor = this.objects[i]._color;
        if(objColor != this.defaultColor) {
            info[i]['color'] = objColor
        }
    }
    return info
}


Coloring.prototype.getEventPosition = function(e, round) {
    e = e || window.event;
    round = (round || 5);

    var target = e.target || e.srcElement,
    rect = target.getBoundingClientRect(),
    offsetX = e.clientX - rect.left,
    offsetY = e.clientY - rect.top;
    var x = Math.round(offsetX / this.container.offsetWidth * 100, round);
    var y = Math.round(offsetY / this.container.offsetHeight * 100, round);

    return [x, y];

}

/**************
 * EXPERIMENT *
 **************/
 var Experiment = function(container, form, instructionData, testData, swatches) {

    if (!(this instanceof Experiment)) {
        return new Experiment(container, form, instructionData, testData)
    } else {

        // HTML structure
        this.container = document.getElementById(container);

        this.instructionContainer = document.createElement('div');
        this.instructionContainer.setAttribute('class', 'constructionContainer pictureContainer');
        this.container.appendChild(this.instructionContainer);

        this.testContainer = document.createElement('div');
        this.testContainer.setAttribute('class', 'testContainer pictureContainer');
        this.container.appendChild(this.testContainer);

        this.form = document.getElementById(form);
        this.formOutput = document.createElement('input');
        this.formOutput.setAttribute('type', 'hidden')
        this.formOutput.setAttribute('name', 'results')
        this.form.appendChild(this.formOutput);
        
        // Submission
        this.form.onsubmit = function() {
            this.getResults();
            this.form.submit()
        }.bind(this)

        this.instructionData = instructionData;
        this.testData = testData; 

        maxHeight = window.innerHeight / 2 - 50;
        numSwatches = Math.max(instructionData['objects'].length, testData['objects'].length);
        this.instructions = new Coloring(this.instructionContainer, instructionData, maxHeight, true, swatches)
        this.test = new Coloring(this.testContainer, testData, maxHeight, false, swatches, numSwatches)

    }
}

Experiment.prototype.getResults = function() {
    var instructionResults = this.instructions.getColoring()
    var testResults = this.test.getColoring()

    var num_analogies = 0;
    for(var i=0; i<testResults.length; i++){
        testResults[i]['analogDesc'] = ''
        testResults[i]['analogId'] = ''
        for(var j=0; j<instructionResults.length; j++) {
            if(instructionResults[j]['color'] == testResults[i]['color']){
                testResults[i]['analogDesc'] = instructionResults[j]['desc']
                testResults[i]['analogId'] = instructionResults[j]['id']
                num_analogies++;
            }
        }
        delete testResults[i]['color']
    }
    
    var results = {
        'instruction': this.instructionData['id'],
        'test': this.testData['id'],
        'analogies': testResults,
        'num_analogies': num_analogies

    }

    this.formOutput.value = JSON.stringify(results)
}





/**************
 * EXPERIMENT *
 **************/

 var Visualization = function(container, form, instructionData, testData, swatches, entropyInstruction, entropyTest) {

    if (!(this instanceof Visualization)) {
        return new Visualization(container, form, instructionData, testData)
    } else {

        // HTML structure
        this.container = document.getElementById(container);

        this.instructionContainer = document.createElement('div');
        this.instructionContainer.setAttribute('class', 'constructionContainer pictureContainer');
        this.container.appendChild(this.instructionContainer);

        this.testContainer = document.createElement('div');
        this.testContainer.setAttribute('class', 'testContainer pictureContainer');
        this.container.appendChild(this.testContainer);

        this.form = document.getElementById(form);
        this.formOutput = document.createElement('input');
        this.formOutput.setAttribute('type', 'hidden')
        this.formOutput.setAttribute('name', 'results')
        this.form.appendChild(this.formOutput);
        

        this.instructionData = instructionData;
        this.testData = testData; 

        maxHeight = window.innerHeight / 2 - 50;
        maxHeight = 350;
        this.instructions = new Coloring(this.instructionContainer, instructionData, maxHeight, 'results', swatches, null, entropyInstruction)
        this.test = new Coloring(this.testContainer, testData, maxHeight, 'results', swatches, null, entropyTest)

    }
}

function componentToHex(c) {
    var hex = Math.round(c).toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {

    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

/**
 * Add class to html element
 * @param {string} className
 */
Raphael.el.addClass = function(className) {
    origClass = this.node.getAttribute('class');
    if (origClass == null || origClass.indexOf(className) == -1) {
        this.node.setAttribute("class", origClass + ' ' + className);
    }
    return this;
};

/**
 * Remove class from html element
 * @param  {string} className
 * @return {object}           element
 */
Raphael.el.removeClass = function(className) {
    origClass = this.node.getAttribute('class') || '';
    this.node.setAttribute('class', origClass.replace(' ' + className, ''));
    return this;
};
