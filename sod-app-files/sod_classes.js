/**
	PROTOTYPE - NOT FINAL
**/

class Layout {
	
	constructor(arrayOfRectangles, name) {
		this.rectangles = arrayOfRectangles;
		this.name = name;
	}

	cellCount() {
		return this.rectangles.length;
	}
	
	getRectangle(index) {
		return this.rectangles[index];
	}
}

class Page {
	
	constructor(tabName, layout) {
		this.name = tabName;
		this.layout = layout;
		this.elementList = [];
		for (var i = 0; i < this.layout.cellCount(); i++) {
			this.elementList.push(0); // 0 denotes no element
		}
	}
	
	clearElements() {
		this.elementList = [];
	}
	
	insertElement(index, elementId) {
		this.elementList[index] = elementId;
	}
	
	removeIndex(index) {
		this.elementList[index] = 0;
	}
	
	// return the index of the layout cell 
	// this mouse click hit, or -1 if it did
	// not hit any
	hitElementIndex(mx, my) {
		var hitIndex = -1;
		for (var i = 0; i < this.layout.cellCount(); i++) {
			var rect = this.layout.getRectangle(i);
			if (mx >= rect[0] && mx <= rect[0]+rect[2] && my >= rect[1] && my <= rect[1]+rect[3]) {
				hitIndex = i;
			}
		}
		return hitIndex;
	}
	
	// assumes rectangles will fit in the canvas
	drawPage(context) {
		for (var i = 0; i < this.elementList.length; i++) {
			var rect = this.layout.getRectangle(i);
			if (this.elementList[i] != 0) {
				context.fillStyle = '#7700ff';
				context.strokeStyle = '#000000';
				context.fillRect(rect[0], rect[1], rect[2], rect[3]);
				context.strokeRect(rect[0], rect[1], rect[2], rect[3]);
				context.fillStyle = '#000000';
				context.fillText('Element ' + this.elementList[i], rect[0] + 20, rect[1] + 20);
			}
			else {
				context.strokeStyle = '#000000';
				context.strokeRect(rect[0], rect[1], rect[2], rect[3]);
			}
		}
	}
}

// helper functions

function createGridLayout(rows, columns, canvasWidth, canvasHeight, hspacing, vspacing) {
	var cellWidth;
	var cellHeight;
	cellWidth = (canvasWidth - (columns+1)*hspacing)/columns;
	cellHeight = (canvasHeight - (rows+1)*vspacing)/rows;
	var rectArray = [];
	
	for (var r = 0; r < rows; r++) {
		for (var c = 0; c < columns; c++) {
			var rect = [
				hspacing + c*(cellWidth+hspacing),
				vspacing + r*(cellHeight+vspacing),
				cellWidth,
				cellHeight
			];
			rectArray.push(rect);
		}
	}
	let layout = new Layout(rectArray, 'Grid Layout');
	return layout;
}





