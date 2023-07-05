<template>
    <div class="relative w-full h-full" id="ports-demo">
        <div class="w-full h-full viewport">
            <screen ref="screen" style="border: none">
                <edge
                    v-for="edge in graph.edges"
                    :data="edge"
                    :nodes="graph.nodes"
                    :key="edge.id"
                ></edge>
                <node
                    :data="node"
                    ref="node"
                    class="overflow-hidden"
                    v-for="node in graph.nodes"
                    :key="node.id"
                >
                    <div class="relative grid">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="absolute icon icon-tabler icon-tabler-x w-[14px] z-[2] cursor-pointer right-[3px] top-[3px] hover:scale-110 transition-all duration-200 stroke-[#F3798A] hover:stroke-[#ec2944]"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            @click="
                                form.area = node.nid;
                                remove();
                            "
                        >
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="absolute icon icon-tabler icon-tabler-x w-[14px] z-[2] cursor-pointer right-[20px] top-[3px] hover:scale-110 transition-all duration-200 stroke-[#65D660] hover:stroke-[#09BD00]"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            @click="
                                arEdit = node;
                                edit = true;
                            "
                        >
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"
                            />
                            <path
                                d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"
                            />
                            <path d="M16 5l3 3" />
                        </svg>
                        <div style="border-bottom: none" class="">
                            <div
                                v-for="input in node.inputs"
                                :key="node.id + ':' + input"
                            >
                                <port
                                    ref="port"
                                    class="w-full hover:bg-green-400"
                                    :id="node.id + ':' + input"
                                    :edgesTo="getInputEdges(node, input)"
                                >
                                    <div
                                        class="port-inner text-center px-[5px] capitalize"
                                        @mousedown.prevent.stop="
                                            (evt) =>
                                                startConnect(
                                                    node,
                                                    { input },
                                                    evt
                                                )
                                        "
                                        @mouseup.prevent.stop="
                                            createConnect(node, {
                                                input,
                                            })
                                        "
                                        :class="
                                            getInputEdges(node, input).length &&
                                            'connected'
                                        "
                                    >
                                        <h1 class="opacity-0">Area</h1>
                                    </div>
                                </port>
                                {{ input.slice(1) }}
                            </div>
                        </div>
                        <div
                            class="node-header px-[6px] cursor-pointer text-center"
                            @click="modal(node.nid)"
                        >
                            <dragable
                                :list="gerencias"
                                item-key="id"
                                group="elementos"
                                animation="300"
                                tag="div"
                                class="px-[15px] justify-center overflow-auto relative z-[10]"
                                drag-class="drag"
                                ghost-class="ghost"
                                @drop="elemento(node.nid)"
                            >
                                <template #header>
                                    <h1 class="capitalize text-[20px]">
                                        {{ node.id }}
                                    </h1>
                                </template>
                                <template #item="{ element }"></template
                            ></dragable>
                        </div>
                        <div style="border-bottom: none">
                            <div
                                v-for="output in node.outputs"
                                :key="node.id + ':' + output"
                            >
                                {{ output.slice(5) }}
                                <port
                                    ref="port"
                                    :id="node.id + ':' + output"
                                    :edgesFrom="getOutputEdges(node, output)"
                                    class="hover:bg-green-400"
                                >
                                    <div
                                        class="port-inner text-center px-[5px]"
                                        @mousedown.prevent.stop="
                                            (evt) =>
                                                startConnect(
                                                    node,
                                                    { output },
                                                    evt
                                                )
                                        "
                                        @mouseup.prevent.stop="
                                            createConnect(node, {
                                                output,
                                            })
                                        "
                                        :class="
                                            getOutputEdges(node, output)
                                                .length && 'connected'
                                        "
                                    >
                                        <h1 class="opacity-0">Area</h1>
                                    </div>
                                </port>
                            </div>
                        </div>
                    </div>
                </node>
            </screen>
        </div>
    </div>
    <EditArea :show="edit" @close="close" :area="arEdit" />
</template>

<script>
import { Screen, Node, Edge, graph, Port } from "vnodes";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import Dragable from "vuedraggable";
import EditArea from "../Modals/EditArea.vue";

export default {
    components: {
        Screen,
        Node,
        Edge,
        Port,
        Dragable,
        EditArea,
    },
    setup() {
        const gerencia = "";
        let modal = false;
        let edit = ref(false);
        let nodos = null;
        let arEdit = ref();
        const form = useForm({
            elemento: "",
            area: "",
        });
        const Nform = useForm({
            nodoA: "",
            nodoB: "",
            nodoC: "",
            nodoD: "",
        });

        return {
            form,
            Nform,
            gerencia,
            modal,
            edit,
            nodos,
            arEdit,
        };
    },
    data() {
        return {
            graph: new graph(),
            connecting: null, // { node: {}, input: str, output: str }
            mousePrev: { x: 0, y: 0 },
            zoom: 1,
        };
    },
    emits: ["elemento", "modal"],
    props: ["rels", "nodos", "areas", "areaRel"],
    methods: {
        close() {
            this.edit = false;
        },
        startConnect(node, { input, output }, evt) {
            if (this.connecting) return;
            const port = this.$refs.port.find(
                (p) => p.id === `${node.id}:${input || output}`
            );
            const edge = input && this.getInputEdges(node, input).reverse()[0];
            if (edge) {
                // edit exiting edge
                edge.active = true;
                this.connecting = {
                    node: this.graph.nodes.find((n) =>
                        input ? edge.from === n.id : edge.to === n.id
                    ),
                    input: output,
                    output: input,
                };
                this.Nform.nodoC = edge;
                this.Nform.nodoD = node;
            } else {
                // new edge
                this.graph.createEdge({
                    from: node.id,
                    to: node.id,
                    fromPort: input || output,
                    toPort: input || output,
                    fromAnchor: { ...port.offset },
                    toAnchor: { ...port.offset },
                    active: true,
                    type: "smooth",
                });
                this.connecting = {
                    node,
                    input,
                    output,
                };

                this.Nform.nodoA = node;
            }
            this.mousePrev = { x: evt.clientX, y: evt.clientY };
            this.zoom = this.$refs.screen.panzoom.getZoom();
        },
        createConnect(node, { input, output }) {
            if (!this.connecting) return;
            if (
                this.isValidConnection({ node, input, output }, this.connecting)
            ) {
                if (input) {
                    this.activeEdge.to = node.id;
                    this.activeEdge.toPort = input;
                    this.Nform.nodoB = node;
                } else if (output) {
                    this.activeEdge.from = node.id;
                    this.activeEdge.fromPort = output;
                    this.Nform.nodoB = this.Nform.nodoA;
                    this.Nform.nodoA = node;
                }
                this.stopConnect();
                this.submit();
            } else {
                this.cancelConnect();
            }
        },
        submit() {
            this.Nform.transform((data) => ({
                ...data,
            })).post(route("area.relacion"), {
                onFinish: () => {
                    this.Nform.reset();
                },
                onStart: () => {
                    this.Nform.reset();
                },
            });
        },
        remove() {
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(route("area.remove"), {
                    onFinish: () => {
                        this.form.reset();
                    },
                    onStart: () => {
                        this.form.reset();
                    },
                });
        },
        delete() {
            this.Nform.transform((data) => ({
                ...data,
            })).post(route("area.destroy"), {
                onFinish: () => {
                    this.Nform.reset();
                },
                onStart: () => {
                    this.Nform.reset();
                },
            });
        },
        cancelConnect() {
            if (!this.connecting) return;
            this.graph.removeEdge(this.activeEdge);
            this.stopConnect();
            this.delete();
        },
        stopConnect() {
            if (this.activeEdge) {
                this.activeEdge.active = false;
            }
            this.$nextTick(() => {
                this.connecting = null;
            });
            this.$nextTick(() => {
                this.graph.graphNodes({
                    spacing: 75,
                    type: "tree",
                    dir: "down",
                });
                this.$refs.screen.zoomNodes(this.graph.nodes);
            });
        },
        isValidConnection(conA, conB) {
            return (
                conA &&
                conB &&
                conA.node &&
                conB.node &&
                conA.node !== conB.node &&
                ((conA.input && conB.output) || (conB.input && conA.output))
            );
        },
        // edges that go to this input
        getInputEdges(node, input) {
            return this.graph.edges.filter(
                (e) => e.to === node.id && e.toPort === input
            );
        },
        // edges that start from this output
        getOutputEdges(node, output) {
            return this.graph.edges.filter(
                (e) => e.from === node.id && e.fromPort === output
            );
        },
        onmousemove(e) {
            if (this.connecting) {
                const offset = {
                    x: (e.clientX - this.mousePrev.x) / this.zoom,
                    y: (e.clientY - this.mousePrev.y) / this.zoom,
                };
                const anchor = this.connecting.input
                    ? this.activeEdge.fromAnchor
                    : this.activeEdge.toAnchor;
                anchor.x += offset.x;
                anchor.y += offset.y;
                this.mousePrev = { x: e.clientX, y: e.clientY };
            }
        },
        area() {
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(route("organigrama.area"), {});
        },
        elemento(element) {
            this.$emit("elemento", { element });
        },
        modal(nds) {
            let a = nds;
            this.$emit("modal", { a });
        },
    },
    computed: {
        activeEdge: (vm) => vm.graph.edges.find((e) => e.active),
    },
    mounted() {
        window.ports = this; // DELETeME
        const node = this.areas;
        node.forEach((nodo) => {
            if (nodo.activo === 1) {
                this.graph.createNode({
                    id: nodo.nombre,
                    nid: nodo.id,
                    inputs: ["i"],
                    outputs: ["o"],
                });
            }
        });
        if (this.$props.areaRel) {
            this.$props.areaRel.forEach((element) => {
                this.graph.createEdge({
                    from: element.nodoA,
                    to: element.nodoB,
                    fromPort: "o",
                    toPort: "i",
                    active: false,
                    type: "smooth",
                });
            });
        }
        this.$nextTick(() => {
            this.graph.graphNodes({
                spacing: 75,
                type: "tree",
                dir: "down",
            });
            this.$refs.screen.zoomNodes(this.graph.nodes);
        });
        document.addEventListener("mouseup", this.cancelConnect);
        document.addEventListener("mousemove", this.onmousemove);
    },
    beforeUpdate() {
        window.ports = this; // DELETeMEconst node = this.areas;
        const node = this.$props.areas;
        this.graph.reset();
        this.$nextTick(() => {
            node.forEach((nodo) => {
                if (nodo.activo === 1) {
                    this.graph.createNode({
                        id: nodo.nombre,
                        nid: nodo.id,
                        inputs: ["i"],
                        outputs: ["o"],
                    });
                }
            });
            if (this.$props.areaRel) {
                this.$props.areaRel.forEach((element) => {
                    this.graph.createEdge({
                        from: element.nodoA,
                        to: element.nodoB,
                        fromPort: "o",
                        toPort: "i",
                        active: false,
                        type: "smooth",
                    });
                });
            }
            this.$nextTick(() => {
                this.graph.graphNodes({
                    spacing: 75,
                    type: "tree",
                    dir: "down",
                });
                this.$refs.screen.zoomNodes(this.graph.nodes);
            });
            document.addEventListener("mouseup", this.cancelConnect);
            document.addEventListener("mousemove", this.onmousemove);
        });
    },
    beforeDestroy() {
        document.removeEventListener("mouseup", this.cancelConnect);
        document.removeEventListener("mousemove", this.onmousemove);
    },
};
</script>

<style scoped>
.port-inner {
    display: inline-block;
    cursor: pointer;
    width: 100%;
}
</style>

<style>
#ports-demo .node .content {
    background-color: #eee;
    box-shadow: 2px 2px 2px 2px rgb(100, 100, 100, 0.2);
    /* outline: 2px solid #555; */
}
#ports-demo .edge {
    stroke: rgb(117, 117, 117);
    stroke-linejoin: round;
    marker-start: none;
    marker-end: none;
    stroke-dasharray: 5px 10px;
    stroke-dashoffset: 1000;
    stroke-linecap: round;
    animation: dash 20s linear infinite;
}
</style>

<style>
@keyframes dash {
    to {
        stroke-dashoffset: 0;
    }
}
</style>
