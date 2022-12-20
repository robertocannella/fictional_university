import './index.scss';
import { v4 as uuidv4 } from 'uuid';
import {InspectorControls,BlockControls,AlignmentToolbar, useBlockProps} from '@wordpress/block-editor';
import {TextControl, Flex, FlexBlock,FlexItem,
    Button, Icon,PanelBody, PanelRow, ColorPicker } from "@wordpress/components";
import {ChromePicker} from 'react-color';

(function () {
    let locked = false;

    wp.data.subscribe(()=>{
        const results = wp.data.select("core/block-editor").getBlocks().filter((block)=>{
            return block.name == "rc-namespace/are-you-paying-attention" && block.attributes.correctAnswer == undefined;
        })

        if (results.length && !locked){
            locked = true;
            wp.data.dispatch("core/editor").lockPostSaving('noanswer')
        }
        if (!results.length && locked){
            locked = false;
            wp.data.dispatch("core/editor").unlockPostSaving('noanswer')
        }
    })
})();


wp.blocks.registerBlockType('rc-namespace/are-you-paying-attention', {
    title: "Are you paying attention?",
    icon: "smiley",
    category: "common",
    description: "Are your users paying attention?",
    example: {attributes:{
            question: "What color is the sky?",
            answers: [
                    { choice: "red", id : "123"},
                    { choice: "blue", id : "456"},
                    { choice: "green", id : "789"}
                ],
            correctAnswer: "456",
            bgColor: "#ebebea",
            theAlignment:  "right"
        }},
    attributes: {
        question: {type: "string"},
        answers: {type: "array",default: [
            { choice: "red", id : uuidv4()},
            { choice: "blue", id : uuidv4()},
            { choice: "green", id : uuidv4()}
          ]},
        correctAnswer: {type: "string",default: undefined},
        bgColor: {type: "string", default: "#ebebeb"},
        bgColorSet: {type: "boolean", default: false},
        theAlignment: {type: "string", default: "left"}
    },
    edit: EditComponent,
    save: () => {return null}
    });





function EditComponent(props) {
    const blockProps = useBlockProps({
        className: "paying-attention-edit-block",
        style: {backgroundColor: props.attributes.bgColor}
    });
    function onChangeAnswer(id,newValue){
        const currentAnswers = [...props.attributes.answers]

        currentAnswers.forEach((answer)=>{
            if (answer.id == id){
                answer.choice = newValue
            }
        })
       props.setAttributes(
            {answers: currentAnswers
            });
    }
    function onMark(id){
        // Wordpress doesn't save defualt attributes. So we have to manually touch every
        // attribute in case the user doesn't change them before saving the widget.

        const currentAnswers = [...props.attributes.answers]
        const currentBgColor =  props.attributes.bgColor
        const currentAlignment = props.attributes.theAlignment
        const currentQuestion = props.attributes.question

        props.setAttributes({
            correctAnswer: id,
            answers: [... currentAnswers],
        })
        console.log(props)
    }

    function onDelete(id){
        const newAnswers = props.attributes.answers.filter((answer)=>{
            return answer.id != id;
        })
        props.setAttributes({
            answers: newAnswers
        })
        if (props.attributes.correctAnswer == id){
            props.setAttributes({
                correctAnswer: undefined
            })
        }
    }
    function updateQuestion(value){
        props.setAttributes({question: value})
    }
    return (
        <div {...blockProps} >
           <BlockControls>
               <AlignmentToolbar value={props.attributes.theAlignment} onChange={value=>{props.setAttributes({theAlignment: value})}}/>
           </BlockControls>
            <InspectorControls>
                <PanelBody title={"Background Color"} initialOpen={true}>
                    <PanelRow>
                        <ColorPicker
                            color={props.attributes.bgColor}
                            onChangeComplete={(picker)=>{
                                props.setAttributes(
                                    {
                                        bgColor: picker.hex,
                                        bgColorSet: true
                                })}}
                            disableAlpha={true}
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
           <TextControl label="Question:" style={{fontSize: "1.5rem"}} value={props.attributes.question} onChange={updateQuestion}/>
            <p style={{fontSize: ".9rem", margin: "1.5rem 0 0.5rem 0"}}>Answers: </p>

            {props.attributes.answers.map((answer) => {
                return (
                    <Flex>
                        <FlexBlock>
                            <TextControl
                                key={answer.id}
                                value={answer.choice}
                                autoFocus={answer.choice == undefined}
                                onChange={(newValue)=>{onChangeAnswer(answer.id,newValue)}}
                            />
                        </FlexBlock>
                        <FlexItem>
                            <Button>
                                <Icon
                                    onClick={()=>{onMark(answer.id)}}
                                    icon={(props.attributes.correctAnswer === answer.id) ? 'star-filled' : 'star-empty'}
                                    className="mark-as-correct"/>
                            </Button>
                        </FlexItem>
                        <FlexItem>
                            <Button isLink className="attention-delete" onClick={()=>{onDelete(answer.id)}}>
                                Delete
                            </Button>
                        </FlexItem>
                    </Flex>
                )
            })}

            <Button isPrimary onClick={()=>{
                props.setAttributes({
                    answers: [...props.attributes.answers, {choice: undefined, id: uuidv4()}]
                })
            }}>
                Add another answer
            </Button>
        </div>
    )
}
