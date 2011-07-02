﻿package nonverblaster {	import flash.display.*;	import de.popforge.events.*;		import com.gskinner.motion.GTween;	import fl.motion.easing.*;		public class ScaleBt extends MovieClip {				private var active				:Boolean = true;		private var targetX				:Number;		private var isActive			:Boolean = false;		private var main				:MovieClip;		private var lastScaling			:*;					public function ScaleBt() {			frame.mouseEnabled = false;		}		public function init(main) {						this.main = main;			initButtons();			ui.grf.text.visible = false;			if(Glo.bal.showScalingButton == "true"){				visible = true;			} else {				visible = false;			}		}		public function initButtons() {			SimpleMouseEventHandler.register( ui.back );			addEventListener( SimpleMouseEvent.RELEASE, sharedButtonHandler );			addEventListener( SimpleMouseEvent.ROLL_OVER, sharedButtonHandler );			addEventListener( SimpleMouseEvent.ROLL_OUT, sharedButtonHandler );		}		public function sharedButtonHandler(event:SimpleMouseEvent):void {			// what button was triggered?			switch (event.type) {				case "onRollOver" :					openIt();					//event.target.alpha = activeAlpha;					break;				case "onRollOut" :					closeIt();					//event.target.alpha = passiveAlpha;					break;				case "onRelease" :					Glo.bal.scaling = !Glo.bal.scaling;					main.applyScaling();					break;			}		}		public function openIt() {			isActive = true;			main.stopHideTimer();			ui.grf.text.visible = true;			targetX = frame.x + frame.width;			new GTween(ui, .45, {x:targetX}, {ease:Quintic.easeOut});		}		public function closeIt() {			isActive = false;			main.startHideTimer();			ui.grf.text.visible = false;			targetX = ui.back.width;			new GTween(ui, .45, {x:targetX}, {ease:Quintic.easeOut});		}		public function adjust(){			trace("lastScaling: " + lastScaling)			trace("Glo.bal.scaling: " + Glo.bal.scaling);			if(Glo.bal.scaling != lastScaling){				lastScaling = Glo.bal.scaling;				switch (Glo.bal.scaling) {					case undefined :					new GTween(ui.grf, .5, {x:-113}, {ease:Quintic.easeInOut});					break;										case true :					new GTween(ui.grf, .5, {x:-113}, {ease:Quintic.easeInOut});					break;											case false :					new GTween(ui.grf, .5, {x:-284}, {ease:Quintic.easeInOut});					break;				}			}		}		public function fadeIn() {			new GTween(this, .5, {alpha:1}, {autoVisible:false, ease:Cubic.easeOut});		}		public function fadeOut() {			new GTween(this, .5, {alpha:0}, {autoVisible:false, ease:Cubic.easeIn});		}		public function setTint(){			Colorizer.colorize(ui.grf, Glo.bal.controlColor);			Colorizer.colorize(ui.back.plane, Glo.bal.controlBackColor);			ui.back.plane.alpha = .6;		}	}}